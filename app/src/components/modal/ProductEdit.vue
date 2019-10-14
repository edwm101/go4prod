<template>
  <v-dialog v-model="dialog" scrollable max-width="900" eager>
    <v-card class="modal">
      <perfect-scrollbar>
        <v-container grid-list-md>
          <v-form ref="form" v-model="valid">
            <v-layout row wrap>
              <v-flex xs12 sm2 right class="pa-3" order-xs3 order-sm1>
                <v-text-field
                  outlined
                  label="Pieces"
                  v-model="part_count"
                  :min="0"
                  type="number"
                  append-icon="mdi-billiards-rack"
                ></v-text-field>
                <v-text-field
                  outlined
                  :min="0"
                  label="Poids"
                  v-model="weight"
                  type="number"
                  append-icon="mdi-alpha-g-box"
                ></v-text-field>
                <v-text-field
                  outlined
                  :min="0"
                  label="Calories"
                  v-model="calorie"
                  type="number"
                  append-icon="mdi-fire"
                ></v-text-field>
                <v-text-field
                  outlined
                  :min="0"
                  label="Personne"
                  v-model="person_count"
                  type="number"
                  append-icon="mdi-account-group"
                ></v-text-field>
              </v-flex>

              <v-flex xs12 sm6 class order-xs1 order-sm2>
                <v-card>
                  <v-card-actions>
                    <template v-if="p_id!=null && p_action=='edit'">
                      <v-btn color="primary" @click="saveProduct">Enregistrer</v-btn>
                      <v-spacer></v-spacer>

                      <v-btn icon @click="deleteProduct" small>
                        <v-icon class="red--text material-icons-outlined">mdi-delete-empty</v-icon>
                      </v-btn>
                    </template>
                    <template v-else>
                      <v-btn
                        color="success"
                        @click="addProduct"
                        v-if="p_action=='add'"
                        :disabled="!valid"
                      >Ajouter</v-btn>

                      <v-spacer></v-spacer>
                      <v-slide-x-reverse-transition>
                        <v-tooltip v-if="formHasErrors" left>
                          <template v-slot:activator="{ on }">
                            <v-btn icon class="my-0" @click="resetForm" v-on="on">
                              <v-icon>mdi-refresh</v-icon>
                            </v-btn>
                          </template>
                          <span>Actualiser le formulaire</span>
                        </v-tooltip>
                      </v-slide-x-reverse-transition>
                    </template>
                  </v-card-actions>
                  <v-progress-linear class="ma-0" :indeterminate="true" v-if="progress"></v-progress-linear>

                  <v-divider></v-divider>

                  <v-layout row wrap class="pt-2 px-3">
                    <v-flex xs9 pa-2>
                      <v-text-field
                        outlined
                        v-model="name"
                        label="Titre"
                        counter
                        maxlength="50"
                        :rules="[rules.required]"
                        required
                      ></v-text-field>
                    </v-flex>
                    <v-flex xs3 pa-2>
                      <v-text-field
                        outlined
                        v-model="price"
                        placeholder="10.00"
                        :rules="[rules.required,rules.price]"
                        required
                        prefix="€"
                      ></v-text-field>
                    </v-flex>
                    <v-flex xs6 px-2 d-flex>
                      <v-select
                        outlined
                        return-object
                        item-text="name"
                        v-model="selectedCategory"
                        :rules="[rules.required_select]"
                        :items="category"
                        label="Categories"
                      ></v-select>
                    </v-flex>
                    <v-flex xs6 px-2 d-flex>
                      <v-select
                        outlined
                        return-object
                        item-text="name"
                        v-model="selectedTva"
                        :rules="[rules.required_select]"
                        :items="tva"
                        label="TVA"
                        required
                      ></v-select>
                    </v-flex>
                  </v-layout>
                </v-card>
                <v-flex xs12 class="pt-5">
                  <v-textarea
                    v-model="description"
                    label="Ingrédients"
                    rows="4"
                    outlined
                    counter
                    maxlength="250"
                  ></v-textarea>
                  <v-combobox
                    outlined
                    v-if="p_type!='option'"
                    v-model="selectedOption"
                    :items="optionSort"
                    item-text="name"
                    label="Sélectionnez vos Options"
                    hide-details
                    outline
                    clearable
                    multiple
                    @input="changeOption"
                  >
                    <template v-slot:selection="data">
                      <v-chip
                        :input-value="data.selected"
                        close
                        clearable
                        @click:close="removeChips('selectedOption',data.index)"
                      >
                        <strong>{{ data.item.name }}</strong>&nbsp;
                      </v-chip>
                    </template>
                    <template v-slot:item="{ index, item }">
                      <span class="body-2 left mr-2">{{ item.name }}</span>
                    </template>
                  </v-combobox>
                </v-flex>
              </v-flex>

              <v-flex xs12 sm4 right class="pa-2" order-xs2 order-sm3>
                <app-upload-multiple-image
                  class="mb-1"
                  :maxImage="5"
                  @set-images="setImages"
                  :data-images="image"
                ></app-upload-multiple-image>
                <v-textarea
                  v-model="short_description"
                  class="pa-0"
                  rows="4"
                  label="Description"
                  outlined
                  counter
                  maxlength="140"
                ></v-textarea>

                <v-combobox
                  hide-detailes
                  v-model="selectedAllergen"
                  :items="allergen"
                  item-text="meta_value"
                  label="Sélectionnez vos Allergène"
                  hide-details
                  outlined
                  multiple
                >
                  <template v-slot:selection="data">
                    <v-chip
                      :input-value="data.selected"
                      close
                      @click:close="removeChips('selectedAllergen',data.index)"
                    >
                      <strong>{{ data.item.meta_value }}</strong>&nbsp;
                    </v-chip>
                  </template>
                </v-combobox>

                <v-combobox
                  hide-detailes
                  v-model="selectedTag"
                  :items="tag"
                  item-text="meta_value"
                  label="Sélectionnez vos Tags"
                  hide-details
                  outlined
                  class="mt-2"
                  multiple
                >
                  <template v-slot:selection="data">
                    <v-chip
                      :input-value="data.selected"
                      close
                      @click:close="removeChips('selectedTag',data.index)"
                    >
                      <strong>{{ data.item.meta_value }}</strong>&nbsp;
                    </v-chip>
                  </template>
                </v-combobox>
              </v-flex>
            </v-layout>
          </v-form>
        </v-container>
      </perfect-scrollbar>
      <v-divider></v-divider>

      <v-card-actions class="pa-1 elevation-3">
        <v-spacer></v-spacer>
        <v-btn color text class="ma-0" @click="dialog = false">Fermer</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import request from "@/common/request";
import { swalsmalltoast, swalDelete } from "@/common/alert";
import {
  r_menuGetInfo,
  r_menuGetProductInfo,
  r_menuAddProduct,
  r_menuPutProduct,
  r_menuDeleteProduct
} from "@/api/menu";

export default {
  props: {
    p_type: {
      type: String
    },
    p_action: {
      default: "add",
      type: String
    },
    p_id: {
      default: null,
      type: Number
    },
    p_setdata: {
      default: false,
      type: Boolean
    }
  },
  data: () => ({
    valid: false,
    progress: false,
    dialog: false,
    serachValue: null,
    is_option: 0,
    name: null,
    description: null,
    short_description: null,
    price: 0.0,
    part_count: 0,
    weight: 0.0,
    calorie: 0,
    person_count: 0,
    tva: [{ id: 10000, name: "test" }],
    selectedTva: [],
    category: [],
    selectedCategory: [],
    option: [],
    selectedOption: [],
    selectedOptionLength: 0,
    allergen: [],
    selectedAllergen: [],
    tag: [],
    selectedTag: [],
    image: [],
    fileList: [],
    formHasErrors: false,
    rules: {
      required: v => !!v || "Ce champ est requis",
      required_select: v => v.length != 0 || "Ce champ est requis",
      price: v => !isNaN(v) || "Le paramètre n'est pas valide Ex 10.00"
    }
  }),
  computed: {
    optionSort() {
      return this.option.sort(this.sortAlpha);
    },
    form() {
      return {
        is_option: this.is_option,
        category_id: this.selectedCategory.id,
        name: this.name,
        description: this.description,
        short_description: this.short_description,
        price: this.price,
        weight: this.weight,
        part_count: this.part_count,
        person_count: this.person_count,
        calorie: this.calorie,
        tva_id: this.selectedTva.id,
        allergen: this.selectedAllergen,
        tag: this.selectedTag,
        option: this.selectedOption,
        product_image: this.image
      };
    }
  },
  watch: {
    async p_setdata() {
      this.resetForm();
      var response = await r_menuGetInfo();

      if (this.p_type == "option") {
        this.is_option = 1;
        this.category = response.data.option;
      } else {
        this.category = response.data.category;
        this.option = response.data.option;
      }
      this.allergen = response.data.allergen;
      this.tag = response.data.tag;

      if (this.p_id != null) {
        var responseProduct = await r_menuGetProductInfo(this.p_id);
        var info = responseProduct.data.info;
        var data = responseProduct.data;
        this.name = info.name;
        this.description = info.description;
        this.short_description = info.short_description;
        this.price = info.price;
        this.weight = info.weight;
        this.part_count = info.part_count;
        this.person_count = info.person_count;
        this.calorie = info.calorie;
        this.selectedCategory = {
          id: info.category_id,
          name: info.category_name,
          image: info.category_name
        };
        this.selectedTva = { id: info.tva_id, name: "test" };
        this.selectedAllergen = data.allergen || [];
        this.selectedTag = data.tag || [];
        this.selectedOption = data.option || [];
        this.selectedOptionLength = this.selectedOption.length;
        this.selectedOption.map(item => {
          this.option.push(item);
        });
        this.image = data.image || [];
      }

      this.dialog = true;
    }
  },
  methods: {
    sortAlpha(a, b) {
      if (a.name < b.name) return -1;
      if (a.name > b.name) return 1;
      return 0;
    },
    changeOption() {
      if (this.selectedOptionLength < this.selectedOption.length) {
        var lastOption = this.selectedOption[this.selectedOption.length - 1];
        this.option.push({ id: lastOption.id, name: lastOption.name + "+" });
      }
      this.selectedOptionLength = this.selectedOption.length;
    },
    removeChips(name, index) {
      this[name].splice(index, 1);
    },
    setImages(fileList, type, index, done) {
      if ((type = "remove")) done();
      this.image = fileList;
    },
    resetForm() {
      this.$refs.form.reset();
      this.formHasErrors = false;
      this.image = [];
    },
    async addProduct() {
      if (this.$refs.form.validate()) {
        try {
          this.progress = true;
          var response = await r_menuAddProduct(this.form);
          swalsmalltoast("success", "Votre produit a été ajouté");
          this.$emit("set-product", "add", response.data.info.id);
        } catch (e) {
          var res = e.response.data.status;
          if (res == "ALREADY_EXISTS")
            swalsmalltoast("error", "Ce nom de produit existe déjà");
        }
        this.progress = false;
      }
    },
    async saveProduct() {
      this.formHasErrors = true;
      var product_id = this.p_id;
      if (this.$refs.form.validate()) {
        this.progress = true;
        try {
          var response = await r_menuPutProduct({
            ...this.form,
            ...{ product_id }
          });
          swalsmalltoast("success", "Votre produit a été modifier");
          this.dialog = false;
          this.$emit("set-product", "save");
        } catch (e) {
          var res = e.response.data.status;
          if (res == "ALREADY_EXISTS")
            swalsmalltoast("error", "Ce nom de produit existe déjà");
        }
        this.progress = false;
      }
    },
    async deleteProduct() {
      this.progress = true;
      var accept = await swalDelete();
      if (accept) {
        r_menuDeleteProduct(this.p_id).then(response => {
          this.dialog = false;
          this.$emit("set-product", "delete");
          swalsmalltoast("success", "Votre produit a été supprimé.");
        });
      }
      this.progress = false;
    }
  }
};
</script>
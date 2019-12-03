<template>
  <div>
    <slot name="tabs"></slot>

    <v-container grid-list-md>
      <router-link class="cp" :to="{ name: 'menuEdit' }">
        <v-banner class="ma-3" single-line v-if="items.length == 0 && loadDone">
          <v-icon slot="icon" color="warning" size="36">mdi-plus</v-icon>
          Il n'y a pas un {{type}}
          <v-spacer></v-spacer>
          <template v-slot:actions>
            <v-btn class="primary" large :to="{ name: 'menuEdit' }" depressed>
              {{
              $t("modify_the_menu")
              }}
            </v-btn>
          </template>
        </v-banner>
      </router-link>
      <v-layout align-space-between row wrap fill-height v-if="loadDone">
        <v-flex xs12 sm10 md9 pa-1 v-if="items.length > 0">
          <v-layout align-space-between row wrap>
            <v-flex xs12 sm5>
              <v-btn large color="primary" @click="openEditProduct(null)">
                <v-icon>mdi-plus</v-icon>
                {{$t("addProduct")}}
              </v-btn>
            </v-flex>
            <v-flex xs12 sm7 v-if="items.length > 0">
              <v-text-field
                class="white mb-2"
                v-model="search"
                :label="$t('search')"
                append-icon="mdi-magnify"
                solo
                hide-details
              ></v-text-field>
            </v-flex>
          </v-layout>
          <v-expansion-panels multiple>
            <app-draggable
              :list="filterItems"
              class="dragArea"
              :disabled="dragDisabled"
              @end="onPositionChange"
            >
              <v-expansion-panel
                v-for="item in filterItems"
                :key="item.id"
                color="expansion"
                class="elevation-0"
              >
                <v-list-item>
                  <v-icon class="material-icons-two-tone handle hidden-xs-only">mdi-drag</v-icon>
                  <v-switch
                    :input-value="item.status"
                    @change="updateCategoryStatus(item.id)"
                    hide-details
                    class="ma-0 pa-0"
                    color="primary darken-1"
                  ></v-switch>

                  <v-spacer></v-spacer>

                  <v-expansion-panel-header expand-icon="mdi-chevron-down">
                    <div>
                      <h4 class>
                        {{ item.name }} ({{
                        (item.products && item.products.length) || 0
                        }})
                      </h4>
                    </div>
                  </v-expansion-panel-header>
                </v-list-item>

                <v-expansion-panel-content class="pa-0">
                  <table class="simple-table">
                    <thead class="thead-dark">
                      <tr>
                        <th scope="col" class="hidden-sm-and-down">#</th>
                        <th>{{ $t("image") }}</th>
                        <th>{{ $t("name") }}</th>
                        <th>{{ $t("price") }}</th>
                        <th>{{ $t("modifier") }}</th>
                      </tr>
                    </thead>
                    <app-draggable
                      v-model="item.products"
                      tag="tbody"
                      class="drogArea"
                      :disabled="dragDisabled"
                      @end="onPositionChange"
                    >
                      <tr v-for="product in item.products" :key="product.id" class="handle">
                        <td scope="col" style="width:20px;" class="hidden-xs-only">
                          <v-icon>mdi-drag</v-icon>
                        </td>
                        <td style="width:70px;max-height:60px;padding:5px;text-align:center">
                          <v-avatar size="50px" class>
                            <img :src="product.image" alt v-if="product.image" />
                          </v-avatar>
                        </td>
                        <td>
                          {{ product.name }}
                          <br />
                          <small class="grey--text">
                            {{
                            product.description
                            }}
                          </small>
                        </td>

                        <td
                          style="width:75px"
                          class="pa-0 text-center"
                        >{{ f_moneyFormat(product.price) }}€</td>

                        <td style="width:15px" class="pa-0 text-center">
                          <v-switch
                            :input-value="product.status"
                            @change="changeProductStatus(product.id)"
                            hide-details
                            class="ma-0 pl-4"
                            color="green darken-1"
                          ></v-switch>

                          <v-menu bottom left>
                            <template v-slot:activator="{ on }">
                              <v-btn icon v-on="on" small>
                                <v-icon>mdi-dots-vertical</v-icon>
                              </v-btn>
                            </template>
                            <v-list class="pa-0">
                              <v-list-item
                                text
                                @click="openEditProduct(product.id, 'edit')"
                                class="blue--text"
                              >
                                <v-list-item-title>
                                  <v-icon left>mdi-file-document-edit-outline</v-icon>Modifier
                                </v-list-item-title>
                              </v-list-item>
                              <v-divider></v-divider>
                              <v-list-item @click="openEditProduct(product.id)" class="green--text">
                                <v-list-item-title>
                                  <v-icon left>mdi-content-copy</v-icon>Dupliquer
                                </v-list-item-title>
                              </v-list-item>
                              <v-divider></v-divider>

                              <v-list-item @click="deleteProduct(product.id)" class="red--text">
                                <v-list-item-title>
                                  <v-icon left>mdi-delete-empty</v-icon>Supprimer
                                </v-list-item-title>
                              </v-list-item>
                            </v-list>
                          </v-menu>
                        </td>
                      </tr>
                    </app-draggable>
                  </table>
                </v-expansion-panel-content>
              </v-expansion-panel>
            </app-draggable>
          </v-expansion-panels>
        </v-flex>
      </v-layout>

      <app-product-edit
        :p_type="type"
        :p_action="product.p_action"
        :p_id="product.p_id"
        :p_setdata="product.p_setdata"
        @set-product="setProduct"
      ></app-product-edit>

      <v-dialog v-model="dialog" width="500">
        <app-simple-add :p_wPicture="true" p_title="Catégories" p_action="category?is_option=0"></app-simple-add>
      </v-dialog>
    </v-container>
  </div>
</template>

<script>
import {
  r_updateProductsPosition,
  r_getProducts,
  r_updateProductStatus,
  r_deleteProduct
} from "@/api/product";
import { r_updateCategoryStatus } from "@/api/category";
import { swaltoast, swalDelete } from "@/common/alert";

export default {
  data() {
    return {
      dialog: false,
      loadDone: false,
      search: "",
      search_befor: "",
      product: { p_action: "", p_setdata: false, p_id: null },
      items: [{}],
      filterItems: [],
      dragDisabled: false,
      screenSize: null
    };
  },
  computed: {
    is_option() {
      return Number(this.$router.currentRoute.params.is_option) !== 1 ? 0 : 1;
    },
    type() {
      return this.is_option === 0 ? "category" : "option";
    }
  },
  watch: {
    async search() {
      if (this.search.length != 0 || this.screenSize == "xs") {
        this.dragDisabled = true;
      } else {
        this.dragDisabled = false;
        await this.setItems();
        this.filterItems = this.items;
        return;
      }

      if (this.search_befor.length > this.search.length) {
        await this.setItems();
      }
      this.search_befor = this.search;

      this.filterItems = this.items.filter((category, key) => {
        category.products = category.products.filter(product => {
          return product.name.toLowerCase().includes(this.search.toLowerCase());
        });
        if (category.products.length > 0) return category;
      });
    }
  },
  methods: {
    onResize() {
      this.screenSize = this.$vuetify.breakpoint.name;
      if (this.screenSize == "xs") {
        this.dragDisabled = true;
      } else {
        this.dragDisabled = false;
      }
    },
    updateCategoryStatus(id) {
      r_updateCategoryStatus(id);
    },
    onPositionChange() {
      var items = [];
      this.items.map((category, i) => {
        var products = [];
        category.products.map((product, j) => {
          products.push(product.id);
        });
        items[i] = { id: category.id, products };
      });
      r_updateProductsPosition(items);
    },
    async setItems() {
      try {
        var response = await r_getProducts(this.is_option);
        this.items = response.data.items;
        this.loadDone = true;
      } catch (e) {
        console.log(e);
      }
    },
    async setProduct(type, payload) {
      await this.setItems();
      this.filterItems = this.items;
    },
    openEditProduct(id, action = "add") {
      this.product.p_action = action;
      this.product.p_id = id;
      this.product.p_setdata = !this.product.p_setdata;
    },
    changeProductStatus(product_id) {
      var response = r_updateProductStatus(product_id);
    },
    async deleteProduct(product_id) {
      var accept = await swalDelete();
      if (accept) {
        r_deleteProduct(product_id).then(response => {
          this.dialog = false;
          this.$emit("set-product", "delete");
          this.setProduct();
          swaltoast("success", this.$t("alert_delete"));
        });
      }
    }
  },
  async created() {
    await this.setItems();
    this.filterItems = this.items;
    this.onResize();
    window.addEventListener("resize", this.onResize);
  }
};
</script>

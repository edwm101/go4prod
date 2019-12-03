<template>
  <div>
    <!-- <v-toolbar class="toolbar-simple">
      <v-toolbar-title left>Products</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-dialog v-model="dialog" scrollable max-width="800px">
        <template v-slot:activator="{ on }">
          <v-btn
            :loading="loading3"
            :disabled="loading3"
            class="primary"
            @click="loader = 'loading3'"
            v-on="on"
          >
            Add a product
            <v-icon right dark>mdi-plus</v-icon>
          </v-btn>
        </template>
        <v-card>
          <v-card-title>Add a new product</v-card-title>
          <v-divider></v-divider>
          <v-card-text style="height: 450px;" class="pa-0">
            <v-row no-gutters>
              <v-col cols="8">
                <v-card outlined class="ma-1 pa-2">
                  <v-row>
                    <v-col md="12">
                      <v-text-field outlined label="Name" counter maxlength="50" required></v-text-field>
                    </v-col>
                    <v-col md="6">
                      <v-text-field
                        outlined
                        label="Regular rate
 (Price)"
                        counter
                        maxlength="50"
                        required
                      ></v-text-field>
                    </v-col>
                    <v-col md="6">
                      <v-text-field outlined label="Promo rate
" counter maxlength="50" required></v-text-field>
                    </v-col>
                  </v-row>
                  <v-textarea outlined label="Short description" counter maxlength="250" required></v-textarea>
                </v-card>
              </v-col>
              <v-col cols="4">
                <v-card tile outlined class="ma-1 pa-1">
                  <app-upload-multiple-image
                    class="mb-1"
                    :maxImage="5"
                    @set-images="setImages"
                    :data-images="image"
                  ></app-upload-multiple-image>

                  <v-select
                    outlined
                    return-object
                    item-text="name"
                    hide-details
                    label="Category"
                    required
                  ></v-select>
                </v-card>
              </v-col>
            </v-row>
          </v-card-text>
          <v-divider></v-divider>
          <v-card-actions>
            <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
            <v-btn color="blue darken-1" text @click="dialog = false">Save</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-toolbar> -->
    <v-container grid-list-md>
      <v-row no-gutters>
        <v-col cols="12" md="8">
          <v-card>
            <v-row no-gutters class="grey lighten-5">
              <v-col class="d-flex pa-2" cols="12" sm="3">
                <v-select
                  :items="sortbyItmes"
                  hide-details
                  label="Order By"
                  v-model="sortby"
                  outlined
                ></v-select>
              </v-col>
              <v-col class="d-flex pa-2" cols="12" sm="3">
                <v-select
                  :items="sort_typeItmes"
                  hide-details
                  label="Sort By"
                  v-model="sort_type"
                  outlined
                ></v-select>
              </v-col>
              <v-col class="d-flex pa-2" cols="12" sm="3">
                <v-text-field label="Min price" v-model="min_price" type="number" hide-details outlined></v-text-field>
              </v-col>
              <v-col class="d-flex pa-2" cols="12" sm="3">
                <v-text-field label="Max price" v-model="max_price" type="number" hide-details outlined></v-text-field>
              </v-col>
            </v-row>
            <v-divider></v-divider>

            <v-text-field
              v-model="search"
              append-icon="mdi-bullseye-arrow"
              label="Search"
              solo
              hide-details
              flat
            ></v-text-field>
            <v-divider></v-divider>
            <v-data-table v-model="selected" :headers="headers" :items="products">
              <template v-slot:item.image="{ item }">
                <img width="60" class="ma-2" :src="item.image" />
              </template>
              <template v-slot:item.action="{ item }">
                <v-icon small class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
                <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
              </template>
            </v-data-table>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>



<script>
import { r_getProducts } from "@/api/admin/products";
export default {
  data() {
    return {
      search: "",
      min_price: 0,
      max_price: 1000000,
      sortby: "Price",
      sort_type: "Desc",
      sortbyItmes: ["Price", "Name", "Views"],
      sort_typeItmes: ["Asc", "Desc"],
      selected: [],
      headers: [
        {
          text: "Image",
          align: "left",
          filterable: false,
          value: "image"
        },
        {
          text: "Name",
          align: "left",
          filterable: false,
          value: "name"
        },
        { text: "Price", value: "price" },
        { text: "Views", value: "views" },
        { text: "Actions", value: "action", align: "right" }
      ],
      products: []
    };
  },
  watch: {
    min_price() {
      this.initData();
    },
    max_price() {
      this.initData();
    },
    sort_type() {
      this.initData();
    },
    sortby() {
      this.initData();
    },
    async search() {
      this.initData();
    }
  },
  methods: {
    async initData() {
      var response = await r_getProducts({
        q: this.search,
        max: 50,
        sort_type: this.sort_type,
        sortby: this.sortby,
        min_price: this.min_price,
        max_price: this.max_price
      });
      this.products = response.data.items;
    }
  },
  async created() {
    this.initData();
  }
};
</script>





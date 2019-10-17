<template>
  <div>
    <v-toolbar class="toolbar-simple">
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
    </v-toolbar>
    <v-container grid-list-md>
      <v-text-field
        v-model="search"
        append-icon="mdi-bullseye-arrow"
        label="Search"
        solo
        hide-details
        class="white mb-2"
      ></v-text-field>
      <v-card class="mx-auto">
        <v-data-table
          v-model="selected"
          show-select
          :headers="headers"
          :items="products"
          :search="search"
        >
          <template v-slot:item.image="{ item }">
            <img width="60" class="ma-2" :src="item.image" />
          </template>
          <template v-slot:item.action="{ item }">
            <v-icon small class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
            <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
          </template>
        </v-data-table>
      </v-card>
    </v-container>
  </div>
</template>



<script>
export default {
  data() {
    return {
      search: "",
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
        { text: "Provider", value: "provider" },
        { text: "Views", value: "views" },
        { text: "Actions", value: "action", align: "right" }
      ],
      products: [
        {
          name: "Téléviseur VEGA 32 LED HD Noir",
          provider: "MyTek",
          views: 61,
          image:
            "https://static.mytek.tn/img/p/7/5/3/3/8/75338-large_default.jpg"
        }
      ]
    };
  }
};
</script>





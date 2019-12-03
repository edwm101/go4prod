<template>
  <div>
    <v-toolbar class="toolbar-simple">
      <v-toolbar-title>
        <v-icon class="mr-2">mdi-wallet-giftcard</v-icon>
        {{$t('discounts')}}
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn color="primary" large depressed @click="dialog=!dialog">{{$t('add')}}</v-btn>
    </v-toolbar>
    <v-card class="ma-3">
      <!-- <v-row no-gutters>
        <v-col md="9" class="pa-1">
          <v-text-field v-model="search" solo append-icon="mdi-search" label="Search" hide-details></v-text-field>
        </v-col>
        <v-col md="3" class="pa-1">
          <v-select label="Actions groupées" :items="actionGroups" hide-details solo></v-select>
        </v-col>
      </v-row> -->

      <v-divider></v-divider>
      <v-data-table
        :search="search"
        v-model="selected"
        :headers="headers"
        item-key="code"
        :items="discounts"
        sort-by="calories"
      >
        <template v-slot:item.action="{ item }">
          <v-icon small class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
          <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
        </template>
        <template v-slot:no-data>
          <v-btn color="primary" @click="initialize">Reset</v-btn>
        </template>
      </v-data-table>
    </v-card>
    <v-dialog v-model="dialog" max-width="900px">
      <v-card>
        <v-form ref="form" v-model="valid">
          <v-card-actions>
            <template v-if="dialogType=='edit'">
              <v-btn color="primary" depressed :disabled="!valid" @click="onUpdate">
                <v-icon left>mdi-content-save</v-icon>Enregistrer
              </v-btn>
              <v-spacer></v-spacer>
              <v-btn color="success" depressed :disabled="!valid" @click="onAdd">
                <v-icon left>mdi-content-copy</v-icon>Dupliquer
              </v-btn>
            </template>
            <template v-else>
              <v-btn
                depressed
                class="success"
                @click="onAdd"
                v-if="dialogType=='add'"
                :disabled="!valid"
              >Ajouter</v-btn>
            </template>
          </v-card-actions>
          <v-divider></v-divider>

          <v-card-text>
            <v-row no-gutters>
              <v-col class="pa-1" cols="12" sm="6" md="4">
                <v-text-field outlined label="code"></v-text-field>
              </v-col>
            </v-row>
          </v-card-text>
          <v-divider></v-divider>
          <v-card-actions>
            <v-tooltip right>
              <template v-slot:activator="{ on }">
                <v-btn icon class="my-0" @click="resetForm" v-on="on">
                  <v-icon>mdi-refresh</v-icon>
                </v-btn>
              </template>
              <span>Actualiser le formulaire</span>
            </v-tooltip>
            <v-spacer></v-spacer>
            <v-btn text @click="dialog=!dialog">Fermer</v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-dialog>
  </div>
</template>



<script>
export default {
  data: () => ({
    dialog: false,
    dialogType: "add",
    valid: false,
    search: "",
    selected: [],
    actionGroups: ["Modifier", "..."],
    headers: [
      { text: "Code", value: "code" },
      { text: "Type de code promo", value: "code_type" },
      { text: "Valeur", value: "code_value" },
      { text: "Description", value: "description" },
      { text: "Usage / Limite", value: "limite" },
      { text: "Date d’expiration", value: "expiration_date" },
      { text: "Actions", value: "action", sortable: false }
    ],
    discounts: []
  }),

  methods: {
    initialize() {
      this.discounts = [
        {
          code: "black_friday",
          code_type: "Remise en pourcentage	",
          code_value: "30",
          description: "Black friday france",
          limite: "0/∞",
          expiration_date: "juin 30, 2020"
        },
        {
          code: "white_friday",
          code_type: "Remise en pourcentage	",
          code_value: "90",
          description: "Black friday france",
          limite: "0/1",
          expiration_date: "juin 30, 2020"
        }
      ];
    },

    editItem(item) {
      this.editedIndex = this.discounts.indexOf(item);
      // this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      const index = this.discounts.indexOf(item);
      confirm("Are you sure you want to delete this item?") &&
        this.discounts.splice(index, 1);
    },
    save() {
      if (this.editedIndex > -1) {
        // Object.assign(this.discounts[this.editedIndex], this.editedItem);
      } else {
        // this.discounts.push(this.editedItem);
      }
    }
  },
  created() {
    this.initialize();
  }
};
</script>
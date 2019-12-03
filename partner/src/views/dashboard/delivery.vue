<template>
  <div>
    <v-toolbar class="toolbar-simple">
      <v-toolbar-title>
        <v-icon class="mr-2">mdi-truck-delivery</v-icon>
        {{$t("truckDelivery")}}
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn color="primary" large depressed @click="openDialog('add')">{{$t("add")}}</v-btn>
    </v-toolbar>
    <span class="cp" @click="openDialog('add')">
      <v-banner class="ma-3" single-line v-if="delivery_men.length == 0 && loadDone">
        <v-icon slot="icon" color="warning" size="36">mdi-plus</v-icon>Il n'y a pas un livreur
      </v-banner>
    </span>

    <v-container>
      <v-row no-gutters>
        <v-col md="4" v-for="(item,key) in delivery_men" :key="item.id" class="pa-1">
          <v-card>
            <v-list-item
              three-line
              class="grey lighten-5 cp"
              @click="openDialog('show',key)"
              v-ripple
            >
              <v-list-item-content>
                <v-list-item-title class="font-weight-medium">{{item.first_name+" "+item.last_name}}</v-list-item-title>
                <v-list-item-subtitle class="caption">{{$t('phone')}} : {{item.phone}}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-divider></v-divider>
            <v-card-actions class="pa-1">
              <v-icon small v-ripple class="ml-2 mr-3 cp" @click="openDialog('show',key)">mdi-eye</v-icon>
              <v-icon small v-ripple class="mr-3 cp" @click="openDialog('edit',key)">mdi-pencil</v-icon>
              <v-icon small v-ripple class="cp" @click="deleteItem(item.id)">mdi-delete</v-icon>
              <v-spacer></v-spacer>
              <v-switch
                hide-details
                @change="onChangeStatus(item.id)"
                v-model="item.status"
                class="ma-0 pa-0"
                color="primary"
              ></v-switch>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>

    <v-dialog v-model="showDialog" max-width="700">
      <v-card class="grey lighten-4" tile>
        <v-list-item three-line class>
          <v-list-item-content>
            <v-list-item-title class="font-weight-medium">{{form.first_name+" "+form.last_name}}</v-list-item-title>
            <v-list-item-subtitle class="caption">{{$t('phone')}} : {{form.phone}}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
        <v-divider></v-divider>
        <v-data-table
          :headers="header_orders"
          item-key="code"
          :items="delivery_orders"
          sort-by="calories"
        >
          <template v-slot:no-data>
            <v-btn color="primary" @click="initialize">Reset</v-btn>
          </template>
        </v-data-table>
      </v-card>
    </v-dialog>

    <v-dialog v-model="editDialog" max-width="350">
      <v-card class="grey lighten-5">
        <v-form ref="form" v-model="valid">
          <v-card-actions>
            <template v-if="dialogType=='edit'">
              <v-btn color="primary" depressed :disabled="!valid" @click="updateItem">
                <v-icon left>mdi-content-save</v-icon>
                {{$t("save")}}
              </v-btn>
            </template>
            <template v-else>
              <v-btn
                class="success"
                @click="addItem"
                v-if="dialogType=='add'"
                :disabled="!valid"
              >{{$t("add")}}</v-btn>
            </template>
          </v-card-actions>
          <v-divider></v-divider>
          <v-row no-gutters>
            <v-col cols="12" sm="6" class="pa-2">
              <v-text-field
                hide-details
                v-model="form.first_name"
                :label="$t('first_name')"
                outlined
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" class="pa-2">
              <v-text-field hide-details v-model="form.last_name" :label="$t('last_name')" outlined></v-text-field>
            </v-col>
            <v-col cols="12" class="pa-2">
              <v-text-field hide-details v-model="form.phone" :label="$t('phone')" outlined></v-text-field>
            </v-col>
          </v-row>
          <v-divider></v-divider>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn text small @click="editDialog=!editDialog">{{$t("close")}}</v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-dialog>
  </div>
</template>


<script>
import {
  r_deleteDeliveryMan,
  r_updateDeliveryMan,
  r_addDeliveryMan,
  r_getDeliveryMans,
  r_updateDeliveryManStatus
} from "@/api/delivery-man";
import { swalsmalltoast, swalDelete } from "@/common/alert";

export default {
  data() {
    return {
      editDialog: false,
      showDialog: false,
      show_add_account: false,
      loadDone: false,
      dialogType: "add",
      valid: false,
      delivery_men: [],
      form: {
        first_name: "",
        last_name: "",
        phone: "",
        email: "",
        password: ""
      },
      header_orders: [
        { text: "Commande", value: "order" },
        { text: "Date", value: "date" },
        { text: "État", value: "status" },
        { text: "Total", value: "total" }
      ],
      delivery_orders: [
        {
          order: "#1015 Michel LE MAY",
          date: "21 Mai 2019",
          status: "En cours",
          total: "99,80€"
        },
        {
          order: "#1011 lilia kassab",
          date: "22 Mai 2019",
          status: "En cours",
          total: "109,80€"
        }
      ]
    };
  },
  methods: {
    resetForm() {
      this.form.first_name = "";
      this.form.last_name = "";
      this.form.phone = "";
      this.form.email = "";
      this.form.password = "";
    },
    openDialog(type = "add", index = "") {
      if (type == "add") {
        this.editDialog = !this.editDialog;

        this.resetForm();
        this.dialogType = "add";
      }
      if (type == "edit") {
        this.editDialog = !this.editDialog;
        this.dialogType = "edit";
        var delivery_men = this.delivery_men[index];
        this.form.id = delivery_men.id;
        this.form.first_name = delivery_men.first_name;
        this.form.last_name = delivery_men.last_name;
        this.form.phone = delivery_men.phone;
      }

      if (type == "show") {
        this.showDialog = !this.showDialog;
        this.dialogType = "show";
        var delivery_men = this.delivery_men[index];
        this.form.id = delivery_men.id;
        this.form.first_name = delivery_men.first_name;
        this.form.last_name = delivery_men.last_name;
        this.form.phone = delivery_men.phone;
      }
    },
    async updateItem() {
      if (this.$refs.form.validate()) {
        var response = await r_updateDeliveryMan(this.form);
        swalsmalltoast("success", this.$t("alert_update"));
        this.editDialog = false;
        this.delivery_men.map((item, key) => {
          if (item.id == response.data.info.id) {
            this.delivery_men[key] = response.data.info;
          }
        });
      }
    },
    async deleteItem(id) {
      var accept = await swalDelete();
      if (accept) {
        r_deleteDeliveryMan(id).then(response => {
          swalsmalltoast("success", this.$t("alert_delete"));
          this.delivery_men.map((item, key) => {
            if (item.id == id) this.delivery_men.splice(key, 1);
          });
        });
      }
    },
    async addItem() {
      if (this.$refs.form.validate()) {
        var response = await r_addDeliveryMan(this.form);
        swalsmalltoast("success", this.$t("alert_add"));
        this.editDialog = false;
        this.delivery_men.unshift(response.data.info);
      }
    },
    onChangeStatus(id) {
      r_updateDeliveryManStatus(id);
    },
    async getDeliveryMans() {
      var response = await r_getDeliveryMans();
      this.delivery_men = response.data.items;
      this.loadDone = true;
    }
  },
  created() {
    this.getDeliveryMans();
  }
};
</script>
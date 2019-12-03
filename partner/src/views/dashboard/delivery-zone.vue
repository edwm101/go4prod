<template>
  <div>
    <v-toolbar class="toolbar-simple">
      <v-toolbar-title>
        <v-icon class="mr-2">mdi-moped</v-icon>
        {{$t('zoneDelivery')}}
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn color="primary" large depressed @click="openDialog('add')">{{$t("add")}}</v-btn>
    </v-toolbar>
    <span class="cp" @click="openDialog('add')">
      <v-banner class="ma-3" single-line v-if="delivery_zones.length == 0 && loadDone">
        <v-icon slot="icon" color="warning" size="36">mdi-plus</v-icon>Il n'y a pas de zone de livraison
      </v-banner>
    </span>

    <v-container grid-list-xs>
      <v-layout row wrap>
        <v-flex xs12 class="pa-1" v-for="(item,key) in delivery_zones" :key="item.id">
          <v-card class="mx-auto grey lighten-4">
            <div class="full-width pa-2">
              <v-layout row wrap>
                <v-flex xs12 md6 @click="openDialog('edit',key)" v-ripple class="cp">
                  <div class="ma-3 float-left">{{ item.zip_code }} {{item.city}}</div>
                </v-flex>
                <v-flex xs4 md1 @click="openDialog('edit',key)" v-ripple class="cp">
                  <div
                    class="headline-1 text-center"
                  >{{ item.min_duration }}-{{ item.max_duration }} min</div>
                  <div class="caption text-center grey--text darken-1">Estimée</div>
                </v-flex>
                <v-flex xs4 md2 @click="openDialog('edit',key)" v-ripple class="cp">
                  <div class="headline-1 text-center">{{ f_moneyFormat(item.shipping_cost) }} €</div>
                  <div class="caption text-center grey--text darken-1">Frais</div>
                </v-flex>
                <v-flex xs4 md1 @click="openDialog('edit',key)" v-ripple class="cp">
                  <div class="headline-1 text-center">{{ f_moneyFormat(item.min_order) }} €</div>
                  <div class="caption text-center grey--text darken-1">Minimum</div>
                </v-flex>
                <v-flex xs12 md2>
                  <v-divider vertical></v-divider>
                  <div class="pa-1 float-right">
                    <v-btn icon>
                      <v-icon small @click="openDialog('edit',key)">mdi-settings</v-icon>
                    </v-btn>
                    <v-btn icon>
                      <v-icon small @click="deleteItem(item.id)">mdi-delete</v-icon>
                    </v-btn>
                    <v-switch
                      small
                      class="mt-2 ml-1 pa-0 float-right"
                      @change="onChangeStatus(item.id)"
                      color="primary"
                      hide-details
                      v-model="item.status"
                    ></v-switch>
                  </div>
                </v-flex>
              </v-layout>
            </div>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>

    <v-dialog v-model="dialog" :eager="true" width="500">
      <v-card>
        <v-form ref="form" v-model="valid">
          <v-card-actions>
            <template v-if="dialogType=='edit'">
              <v-btn color="primary" depressed :disabled="!valid" @click="updateItem">
                <v-icon left>mdi-content-save</v-icon>Enregistrer
              </v-btn>
              <v-spacer></v-spacer>
              <v-btn color="success" depressed :disabled="!valid" @click="addItem">
                <v-icon left>mdi-content-copy</v-icon>Dupliquer
              </v-btn>
            </template>
            <template v-else>
              <v-btn
                depressed
                class="success"
                @click="addItem"
                v-if="dialogType=='add'"
                :disabled="!valid"
              >Ajouter</v-btn>
            </template>
          </v-card-actions>
          <v-divider></v-divider>
          <v-layout row wrap class="pa-3 ma-0">
            <v-flex xs12 md7 class="pa-2">
              <v-autocomplete
                :auto-select-first="true"
                v-model="adress"
                :cache-items="true"
                :search-input.sync="search_adress"
                outlined
                :items="delivery_adress"
                label="Ville "
                item-text="city"
                :rules="[rules.required]"
                return-object
              ></v-autocomplete>
            </v-flex>
            <v-flex xs12 md5 class="pa-2">
              <v-text-field
                outlined
                v-model="zip_code"
                :rules="[rules.required]"
                label="Code postal"
              ></v-text-field>
            </v-flex>

            <v-flex xs12 md4 class="pa-2">
              <v-text-field
                v-model="duration"
                label="Délai de livraison estimé"
                v-mask="duration_mask"
                suffix="min"
                placeholder="30-40"
                hint="Ex: 30-40 min"
                persistent-hint
              ></v-text-field>
            </v-flex>

            <v-flex xs12 md4 class="pa-2">
              <v-text-field
                outlined
                v-model="shipping_cost"
                label="Frais"
                hint="Ex: 0.00€"
                suffix="€"
                :rules="[rules.price]"
                persistent-hint
              ></v-text-field>
            </v-flex>

            <v-flex xs12 md4 class="pa-2">
              <v-text-field
                outlined
                v-model="min_order"
                label="Minimum"
                required
                suffix="€"
                :rules="[rules.price]"
                hint="Ex: 20.00€"
                persistent-hint
              ></v-text-field>
            </v-flex>
          </v-layout>
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
import {
  r_deleteDeliveryZone,
  r_updateDeliveryZone,
  r_addDeliveryZone,
  r_findDeliveryZones,
  r_getDeliveryZones,
  r_updateDeliveryZoneStatus
} from "@/api/delivery-zone";
import { mask } from "vue-the-mask";
import { swalsmalltoast, swaltoast, swalDelete } from "@/common/alert";

export default {
  directives: {
    mask
  },
  data() {
    return {
      dialog: false,
      loadDone: false,
      dialogType: "add",
      valid: false,
      delivery_zones: [],
      delivery_adress: [],
      duration_mask: "##-##",
      id: null,
      adress: null,
      zip_code: null,
      search_adress: null,
      min_order: null,
      shipping_cost: null,
      duration: 3040,
      rules: {
        required: v => !!v || this.$i18n.t("rule_required"),
        required_select: v => v.length != 0 || this.$i18n.t("rule_required"),
        price: v => !isNaN(v) || this.$i18n.t("rule_price")
      }
    };
  },
  computed: {
    form() {
      var duration = this.duration.split("-");
      return {
        id: this.id,
        city: this.search_adress,
        zip_code: this.zip_code,
        min_order: this.min_order,
        shipping_cost: this.shipping_cost,
        min_duration: duration[0],
        max_duration: duration[1]
      };
    }
  },
  watch: {
    search_adress: async function(val) {
      val = val.replace(" ", "+");
      var response = await r_findDeliveryZones(val);
      this.delivery_adress = response.data.items;
    },
    adress: function(val) {
      this.zip_code = val.zip_code;
    }
  },
  methods: {
    resetForm() {
      this.search_adress = null;
      this.zip_code = null;
      this.duration = "30-40";
      this.min_order = 0.0;
      this.shipping_cost = 0.0;
    },
    openDialog(type = "add", index = "") {
      this.dialog = !this.dialog;
      if (type == "add") {
        this.resetForm();
        this.dialogType = "add";
      }
      if (type == "edit") {
        this.dialogType = "edit";
        var delivery_zone = this.delivery_zones[index];

        this.id = delivery_zone.id;
        this.search_adress = delivery_zone.city;

        this.adress = {
          city: delivery_zone.city,
          zip_code: delivery_zone.zip_code
        };
        this.min_order = delivery_zone.min_order;
        this.shipping_cost = delivery_zone.shipping_cost;
        this.duration =
          delivery_zone.min_duration + "-" + delivery_zone.max_duration;
      }
    },
    async updateItem() {
      if (this.$refs.form.validate()) {
        var response = await r_updateDeliveryZone(this.form);
        swalsmalltoast("success", this.$t("alert_update"));
        this.dialog = false;
        this.delivery_zones.map((item, key) => {
          if (item.id == response.data.info.id) {
            this.delivery_zones[key] = response.data.info;
          }
        });
      }
    },
    async deleteItem(id) {
      var accept = await swalDelete();
      if (accept) {
        r_deleteDeliveryZone(id).then(response => {
          swalsmalltoast("success", this.$t("alert_delete"));
          this.delivery_zones.map((item, key) => {
            if (item.id == id) this.delivery_zones.splice(key, 1);
          });
        });
      }
    },
    async addItem() {
      if (this.$refs.form.validate()) {
        var response = await r_addDeliveryZone(this.form);
        swalsmalltoast("success", this.$t("alert_add"));
        this.delivery_zones.unshift(response.data.info);
      }
    },
    onChangeStatus(id) {
      r_updateDeliveryZoneStatus(id);
    },
    async getDeliveryZones() {
      var response = await r_getDeliveryZones();
      this.delivery_zones = response.data.items;
      this.loadDone = true;
    }
  },
  created() {
    this.getDeliveryZones();
  }
};
</script>
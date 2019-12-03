<template>
  <div>
    <div>
      <v-toolbar class="toolbar-simple">
        <v-toolbar-title>{{$t("allRestaurants")}}</v-toolbar-title>
      </v-toolbar>
      <v-divider></v-divider>

      <v-container grid-list-xs>
        <v-layout row wrap>
          <v-flex xs12 sm6 md4 class="pa-1">
            <app-button-big height="102" :to="{name:'add-restaurant'}">Ajouter un restaurant</app-button-big>
          </v-flex>
          <v-flex xs12 sm6 md4 class="pa-1" v-for="restaurant in restaurants" :key="restaurant.id">
            <v-card>
              <v-list-item
                three-line
                class="cp  grey lighten-5"
                v-ripple
                @click="enterResto(restaurant.id,'menu')"
              >
                <v-list-item-content>
                  <v-list-item-title class="font-weight-medium">
                    {{restaurant.name}}
                    <span class="overline ml-2">{{"#"+restaurant.id}}</span>
                  </v-list-item-title>
                  <v-list-item-subtitle class="caption">{{ restaurant.city }}</v-list-item-subtitle>
                </v-list-item-content>
                <v-avatar v-if="restaurant.logo" tile size="50" color="" >
                  <img :src="restaurant.logo" />
                </v-avatar>
              </v-list-item>
              <v-divider></v-divider>
              <v-card-actions class="pa-1 grey lighten-4">
                <v-menu bottom origin="center center" transition="scale-transition">
                  <template v-slot:activator="{ on }">
                    <v-btn text small class="right" v-on="on">
                      <v-icon small>mdi-dots-vertical</v-icon>
                    </v-btn>
                  </template>
                  <v-card>
                    <v-list>
                      <v-list-item
                        :to="{name:'menu'}"
                        @click="enterResto(restaurant.id,'menu')"
                        text
                      >
                        <v-icon left>mdi-silverware-fork-knife</v-icon>Menu
                      </v-list-item>
                      <v-list-item
                        :to="{name:'account'}"
                        @click="enterResto(restaurant.id,'menu')"
                        text
                      >
                        <v-icon left>mdi-settings</v-icon>Mon restaurant
                      </v-list-item>
                    </v-list>
                  </v-card>
                </v-menu>
                <v-spacer></v-spacer>
                <v-switch
                  @change="goToPayment"
                  class="ma-0 pa-0"
                  color="primary"
                  hide-details
                  v-model="restaurant.open_status"
                ></v-switch>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </div>
  </div>
</template>


<script>
import { r_getProviders } from "@/api/providers";
export default {
  data() {
    return {
      dialog: false,
      resto_state: false,
      restaurants: []
    };
  },
  methods: {
    goToPayment() {},
    async enterResto(restaurnt_id, name) {
      await this.$store.dispatch("userRestoEnter", restaurnt_id);
      this.$router.push({ name });
    }
  },
  async created() {
    var response = await r_getProviders();
    this.restaurants = response.data.items;
  }
};
</script>
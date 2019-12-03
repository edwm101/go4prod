<template>
  <v-app id="inspire">
    <!-- SideBar -->
    <v-navigation-drawer
      v-model="drawer"
      :mini-variant="mini"
      :clipped="$vuetify.breakpoint.lgAndUp"
      app
      class="elevation-2"
    >
      <v-list>
        <v-list-item v-if="mini" @click.stop="mini = !mini">
          <v-list-item-action class="py-2">
            <v-icon>mdi-chevron-right</v-icon>
          </v-list-item-action>
        </v-list-item>
        <v-list-item class="px-1 py-2" v-if="!mini">
          <v-list-item-avatar size="50" tile class="elevation-2 py-0 mr-2 my-0">
            <v-img :src="resto_logo" contain></v-img>
          </v-list-item-avatar>
          <v-list-item-content class="py-0">
            <v-list-item-title class="body-3 font-weight-bold">{{resto_name}}</v-list-item-title>
            <div>
              <v-switch
                flat
                hide-details
                color="orange darken-3"
                class="ma-0 ml-0 body-1"
                v-model="resto_state"
                :label="(resto_state === true) ? $t('open') : $t('close')"
              ></v-switch>
            </div>
          </v-list-item-content>

          <v-list-item-action class="my-0 m-0">
            <v-btn small icon @click.stop="mini = !mini">
              <v-icon>mdi-chevron-left</v-icon>
            </v-btn>
          </v-list-item-action>
        </v-list-item>
      </v-list>
      <v-divider></v-divider>
      <v-list class="pa-0" dense>
        <perfect-scrollbar class="psSiderbarList">
          <template v-for="item in items">
            <v-layout v-if="item.heading" :key="item.heading" row align-center>
              <v-flex xs6>
                <v-subheader v-if="item.heading">{{ item.heading }}</v-subheader>
              </v-flex>
            </v-layout>
            <v-list-group
              v-else-if="item.children"
              :key="item.text"
              v-model="item.model"
              :prepend-icon="item.model ? item.icon : item['icon-alt']"
              append-icon
            >
              <template v-slot:activator>
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title>{{ item.text }}</v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </template>
              <v-list-item v-for="(child, i) in item.children" :key="i" :to="item.path">
                <v-list-item-action v-if="child.icon">
                  <v-icon>{{ child.icon }}</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                  <v-list-item-title>{{ child.text }}</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list-group>
            <v-list-item v-else :key="item.text" :to="item.path">
              <v-tooltip right>
                <template v-slot:activator="{ on }">
                  <v-list-item-action v-on="on" class>
                    <v-icon>{{ item.icon }}</v-icon>
                  </v-list-item-action>
                </template>
                <span>{{ $t(item.text) }}</span>
              </v-tooltip>
              <v-list-item-content>
                <v-list-item-title>{{ $t(item.text) }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </template>
        </perfect-scrollbar>
      </v-list>
    </v-navigation-drawer>

    <!-- Header -->
    <app-header></app-header>

    <!-- Views -->
    <v-content>
      <v-slide-y-transition mode="out-in">
        <router-view></router-view>
      </v-slide-y-transition>
    </v-content>

    <!-- Footer -->
    <v-footer class="pa-3 transparent">
      <v-spacer></v-spacer>
      <div class="text-xs-left">{{name}} &copy; {{ new Date().getFullYear() }}</div>
    </v-footer>
  </v-app>
</template>

<script>
import { mapGetters } from "vuex";
import { all, allSettled } from "q";

export default {
  data: () => ({
    resto_state: true,
    mini: false,
    name: process.env.VUE_APP_NAME,
    items: [
      {
        icon: "mdi-clipboard-text",
        text: "orderToday",
        path: "/orders/active"
      },
      {
        icon: "mdi-calendar-today",
        text: "orderScheduled",
        path: "/orders/scheduled"
      },
      {
        icon: "mdi-history",
        text: "orderHistory",
        path: "/orders/history"
      },
      {
        icon: "mdi-silverware",
        text: "menu",
        path: { name: "menuCategory", params: { is_option: 0 } }
      },
      {
        icon: "mdi-moped",
        text: "zoneDelivery",
        path: { name: "delivery-zone" }
      },
      {
        icon: "mdi-av-timer",
        text: "schedule",
        path: { name: "operating-time" }
      },
      {
        icon: "mdi-wallet-giftcard",
        text: "discounts",
        path: { name: "discounts" }
      },
      {
        icon: "mdi-truck-delivery",
        text: "truckDelivery",
        path: { name: "delivery" }
      },
      {
        icon: "mdi-chart-bar",
        text: "statistics",
        path: { name: "analytics" }
      },
      {
        icon: "mdi-account-group",
        text: "clients",
        path: { name: "clients" }
      },
      {
        icon: "mdi-silverware-fork-knife",
        text: "myResto",
        path: { name: "my-restaurant" }
      },
      { icon: "mdi-lifebuoy", text: "help", path: { name: "help" } }
    ]
  }),
  props: {
    source: String
  },
  computed: {
    ...mapGetters([
      "resto_name",
      "resto_logo",
      "resto_open_status",
      "resto_slogan"
    ]),
    drawer: {
      get() {
        return this.$store.getters.drawer;
      },
      set(val) {
        if (this.$store.getters.drawer != val) {
          this.$store.commit("LAYOUT_INIT_DRAWER", val);
        }
      }
    }
  }
};
</script>



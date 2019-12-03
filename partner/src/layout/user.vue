<template>
  <v-app id="inspire">
    <!-- SideBar -->
    <v-navigation-drawer
      v-model="drawer"
      :mini-variant="mini"
      :clipped="$vuetify.breakpoint.lgAndUp"
      app
      class="elevation-1 lighten-4"
    >
      <v-list dense>
        <perfect-scrollbar class="psSiderbarList">
          <template v-for="item in items">
            <v-layout v-if="item.heading" :key="item.heading" row align-center>
              <v-flex xs6>
                <v-subheader v-if="item.heading">{{ item.heading }}</v-subheader>
              </v-flex>
              <v-flex xs6 class="text-xs-center">
                <a href="#!" class="body-2 black--text">EDIT</a>
              </v-flex>
            </v-layout>
            <v-list-group
              class="ma-0 pa-0"
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
              <v-list-item
                v-for="(child, i) in item.children"
                :key="i"
                :to="{name : item.name}"
                exact
              >
                <v-list-item-action v-if="child.icon">
                  <v-icon>{{ child.icon }}</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                  <v-list-item-title>{{ child.text }}</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list-group>
            <v-list-item v-else :key="item.text" :to="{name : item.name}">
              <v-tooltip right>
                <template v-slot:activator="{ on }">
                  <v-list-item-action v-on="on">
                    <v-icon>{{ item.icon }}</v-icon>
                  </v-list-item-action>
                </template>
                <span v-if="mini">{{ $t(item.text) }}</span>
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
  </v-app>
</template>

<script>
export default {
  data: () => ({
    resto_state: true,
    mini: true,
    items: [
      { icon: "mdi-silverware", text: "allRestaurants", name: "restaurants" },
      {
        icon: "mdi-credit-card",
        text: "payment",
        name: "payment"
      },
      {
        icon: "mdi-settings",
        text: "myAccount",
        name: "account"
      }
    ]
  }),
  computed: {
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



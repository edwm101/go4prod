<template>
  <v-app id="inspire">
    <!-- SideBar -->
    <v-navigation-drawer
      v-model="drawer"
      :mini-variant="mini"
      :clipped="$vuetify.breakpoint.lgAndUp"
      app
      class="elevation-1"
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
    <v-app-bar :clipped-left="$vuetify.breakpoint.lgAndUp" class="px-2" dark app fixed>
      <v-progress-linear
        style="z-index:9"
        :height="4"
        :active="load_progress"
        :indeterminate="load_progress"
        fixed
        bottom
        color="green"
      ></v-progress-linear>

      <v-btn @click.stop="drawer =!drawer" icon fab small class="mr-3 small">
        <v-icon>mdi-menu</v-icon>
      </v-btn>

      <v-btn icon small :to="{name:'my-resto'}" class="mr-2">
        <v-icon>mdi-home</v-icon>
      </v-btn>
      <v-divider vertical></v-divider>

      <v-toolbar-title style="width: 300px">
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title class="body-2">{{$t("welcome")}}, {{full_name}}</v-list-item-title>
            <v-list-item-subtitle class="ma-0 grey--text lighten-2">Admin</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-toolbar-title>

      <v-spacer></v-spacer>
      <v-btn icon @click="$i18n.locale = 'en'">
        <v-icon>mdi-google-translate</v-icon>
      </v-btn>
      <app-full-screen></app-full-screen>

      <v-btn icon>
        <v-icon>mdi-settings</v-icon>
      </v-btn>
      <v-menu bottom origin="center center" transition="scale-transition">
        <template v-slot:activator="{ on }">
          <v-btn icon v-on="on">
            <v-avatar size="32px" color="indigo">
              <v-icon color="white">mdi-account-circle</v-icon>
            </v-avatar>
          </v-btn>
        </template>
        <v-card>
          <v-list>
            <v-list-item :to="{name:'my-account'}" text>
              <v-icon left>mdi-account</v-icon>My account
            </v-list-item>
            <v-list-item @click="userSignOut" text>
              <v-icon left>mdi-lock</v-icon>Logout
            </v-list-item>
          </v-list>
        </v-card>
      </v-menu>
    </v-app-bar>
    <!-- Views -->
    <v-content>
      <v-slide-y-transition mode="out-in">
        <router-view></router-view>
      </v-slide-y-transition>
    </v-content>
  </v-app>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  data: () => ({
    resto_state: true,
    drawer: true,
    mini: true,
    items: [
       {
        icon: "mdi-cart",
        text: "Products",
        name: "admin-products"
      },
      {
        icon: "mdi-robot",
        text: "Crawling",
        name: "admin-crawling"
      }
    ]
  }),
  methods: {
    userSignOut() {
      this.$store.dispatch("userSignOut");
    },
    setDrawer() {
      this.$store.commit("LAYOUT_SET_DRAWER");
    }
  },
  computed: {
    ...mapGetters(["full_name", "load_progress"])
  }
};
</script>
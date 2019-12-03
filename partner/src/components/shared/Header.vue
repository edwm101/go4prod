<template>
  <div>
    <v-app-bar :clipped-left="$vuetify.breakpoint.lgAndUp" class="px-2" dark app fixed>
      <v-progress-linear
        style="z-index:9"
        :height="4"
        :active="load_progress"
        :indeterminate="load_progress"
        fixed
        bottom
        color="#1e6cb9"
      ></v-progress-linear>

      <v-btn @click.stop="setDrawer" icon fab small class="mr-3 small">
        <v-icon>mdi-menu</v-icon>
      </v-btn>

      <v-btn icon small :to="{name:'restaurants'}" class="mr-2">
        <v-icon>mdi-home</v-icon>
      </v-btn>
      <v-divider vertical></v-divider>

      <v-toolbar-title style="width: 300px">
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title class="body-2">{{$t("welcome")}}, {{full_name}}</v-list-item-title>
            <v-list-item-subtitle class="ma-0 grey--text lighten-2">{{$t("manager")}}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-toolbar-title>

      <v-spacer></v-spacer>
      <v-btn icon small @click="$vuetify.lang.current = 'en';$i18n.locale = 'en'">En</v-btn>|
      <v-btn icon small @click="$vuetify.lang.current = 'fr';$i18n.locale = 'fr'">Fr</v-btn>
      <app-full-screen></app-full-screen>

      <v-menu offset-y bottom left transition="slide-y-transition">
        <template v-slot:activator="{ on }">
          <v-btn v-on="on" icon text>
            <v-badge color="red" right small overlap>
              <template v-slot:badge>
                <small>{{notifications.length}}</small>
              </template>
              <v-icon>mdi-bell</v-icon>
            </v-badge>
          </v-btn>
        </template>
        <v-card>
          <v-toolbar dense class="elevation-1">
            <v-icon small>mdi-bell</v-icon>
            <v-toolbar-title class="body-2">notifications</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn class="primary" text>Voir tout</v-btn>
          </v-toolbar>

          <v-list dense class="pa-0">
            <div v-for="(notification,index) in notifications" :key="index">
              <v-list-item>
                <v-list-item-title>
                  <v-icon left>{{notification.icon}}</v-icon>
                  {{notification.title}}
                  <small>- {{notification.time}}</small>
                </v-list-item-title>
              </v-list-item>
              <v-divider></v-divider>
            </div>
          </v-list>
        </v-card>
      </v-menu>

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
            <v-list-item :to="{name:'restaurants'}" text>
              <v-icon left>mdi-silverware-fork-knife</v-icon>Mes restaurant
            </v-list-item>
            <v-list-item :to="{name:'account'}" text>
              <v-icon left>mdi-account</v-icon>Mon profil
            </v-list-item>
            <v-list-item @click="userSignOut" text>
              <v-icon left>mdi-lock</v-icon>Déconnexion
            </v-list-item>
          </v-list>
        </v-card>
      </v-menu>
    </v-app-bar>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  data: () => ({
    notifications: [
      {
        icon: "mdi-check",
        title: "Il y a une nouvelle commande",
        time: "Il y a 2 minutes"
      },
      {
        icon: "mdi-alert-circle",
        title: "votre abonnement n'a pas pu être activé",
        time: "Il y a 30 minutes"
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
  },
  created() {
    this.$store.commit(
      "LAYOUT_INIT_DRAWER",
      !this.$vuetify.breakpoint.mdAndDown
    );
  }
};
</script>
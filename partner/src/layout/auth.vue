<template>
  <v-app id="inspire" class="backAuth ">
    <v-layout row justify-center class="overflow">
      <v-flex d-flex xs12 md4 class="pa-1">
          <v-layout align-center column pa-3 >
            <h1 class="display-2 font-weight-thin mb-2" >{{title}}</h1>
            <h4 class="subheading mb-3">Construisez votre restaurant aujourd'hui!</h4>
    <v-fade-transition mode="out-in">
              <router-view></router-view>
            </v-fade-transition>
          </v-layout>
      </v-flex>
    </v-layout>
  </v-app>
</template>

<script>
export default {
  data: () => ({
    title : process.env.VUE_APP_NAME
  }),
  props: {
    source: String
  }
};
</script>



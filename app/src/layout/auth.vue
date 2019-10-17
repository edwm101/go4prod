<template>
  <v-app id="inspire" class="">
    <v-layout row justify-center class="overflow">
      <v-flex d-flex xs12 md4 class="pa-1">
        <v-layout align-center column pa-3>
          <h1 class="display-2 font-weight-thin mb-2">Go4Prod</h1>
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
  data: () => ({}),
  props: {
    source: String
  }
};
</script>



<template>
  <v-card style="height:133px;width:100%" ><v-btn :to="to" class="ma-0 pa-0" large  text style="height:133px;width:100%">
    <v-icon style="font-size :50px">{{icon}}</v-icon>
      <slot></slot>
  </v-btn></v-card>
</template>

<script>
export default {
  props: {
    icon: {
      default: "mdi-plus",
      type: String
    },
    to: {
      default: null
    }
  }
};
</script>
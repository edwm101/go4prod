<template>
  <v-card :style="btnStyle">
    <v-btn :to="to" class="ma-0 pa-0" large text :style="btnStyle">
      <v-icon style="font-size :50px">{{icon}}</v-icon>
      <slot></slot>
    </v-btn>
  </v-card>
</template>

<script>
export default {
  props: {
    icon: {
      default: "mdi-plus",
      type: String
    },
    height: { default: "90" },
    to: {
      default: null
    }
  },
  computed: {
    btnStyle() {
      return "height:" + this.height + "px ; width:100%";
    }
  }
};
</script>
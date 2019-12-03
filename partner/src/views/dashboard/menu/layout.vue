
<template>
  <div>
    <!-- <perfect-scrollbar> -->
    <router-view :key="$route.fullPath">
      <template v-slot:tabs>
        <div style="height:48px;width:100%">
          <v-tabs app show-arrows class="dashViewTabs">
            <v-tab
              name="tabs"
              :to="{name : 'menuCategory',params:{is_option:0}}"
              exact
              replace
            >{{ $t("categories") }}</v-tab>
            <v-tab
              name="tabs"
              :to="{name : 'menuCategory',params:{is_option:1}}"
              exact
              replace
            >{{ $t("options") }}</v-tab>
            <v-tab :to="{name : 'menuEdit'}" exact>{{ $t("modify_the_menu") }}</v-tab>
          </v-tabs>
        </div>
      </template>
      <template v-slot:toolbar></template>
    </router-view>
    <!-- </perfect-scrollbar> -->
  </div>
</template>

<script>
export default {
  created() {
    window.addEventListener("scroll", function(event) {
      var scroll = this.scrollY;
      console.log(scroll);
    });
  }
};
</script>
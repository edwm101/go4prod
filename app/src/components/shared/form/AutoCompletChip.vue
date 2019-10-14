<template>
  <v-autocomplete
    v-model="selected_items"
    :items="items"
    box
    hide-details
    class="white mb-2 elevation-2"
    :label="label"
    :item-text="item_text"
    return-object
    multiple
    append-icon="scatter_plot"
  >
    <template slot="selection" slot-scope="data" >
      <v-chip
        :selected="data.selected"
        close
        class="chip--select-multi white elevation-1 ma-1 pa-0"
        @input="data.parent.selectItem(data.item)"
      >
        <v-avatar v-if="showImage">
          <img :src="data.item.avatar" />
        </v-avatar>
        {{ data.item[item_text] }} 
      </v-chip>
    </template>
    <template slot="item" slot-scope="data" >
      <template v-if="typeof data.item !== 'object'">
        <v-list-item-content v-text="data.item"></v-list-item-content>
      </template>
      <template v-else>
        <v-list-item-avatar v-if="showImage">
          <img :src="data.item.avatar" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title v-html="data.item[item_text]"></v-list-item-title>
          <v-list-item-sub-title v-html="data.item.group"></v-list-item-sub-title>
        </v-list-item-content>
      </template>
    </template>
  </v-autocomplete>
</template>

<script>
export default {
  props: {
    items: {},
    selected_items:{ default: [],type:Array},
    label: { default: "select" },
    showImage: {},
    item_text: { default: "name",type:String }
  },
  data() {
    return {
      autoUpdate: true,
      isUpdating: false
    };
  },

  watch: {
    selected_items(val) {
      this.$emit("selectedItems", this.selected_items);
      if (val) {
        setTimeout(() => (this.isUpdating = false), 3000);
      }
    }
  }
};
</script>
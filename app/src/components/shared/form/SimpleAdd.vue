<template>
  <v-card>
    <div>
      <v-toolbar class="elevation-0" dense>
        <v-toolbar-title class="subtitle-2">{{p_title}}</v-toolbar-title>
        <v-spacer></v-spacer>
        <slot name="toolbar-action"></slot>
      </v-toolbar>

      <v-divider></v-divider>

      <app-upload-multiple-image
        :multiple="false"
        v-if="p_wPicture"
        :maxImage="1"
        @set-images="setImage"
        :data-images="image"
      ></app-upload-multiple-image>

      <v-divider v-if="p_wPicture"></v-divider>

      <v-text-field
        v-model="item"
        hide-details
        :label="'Écrivez vos '+p_title+' ici'"
        solo
        flat
        @keydown.enter="create"
        class="mb-0"
        append-icon="mdi-plus"
        @click:append="create"
      ></v-text-field>
      <v-divider></v-divider>
      <v-list class="pa-0">
        <perfect-scrollbar :class="[ p_wPicture ? 'ps400' : 'ps200' ]">
          <v-list-item-group v-model="index">
            <v-slide-y-transition group item="v-list" mode="out-in" v-if="items.length > 0">
              <template v-for="(item, i) in items">
                <v-list-item :key="item.id">
                  <v-list-item-avatar
                    v-if="p_wPicture && item.image!=null"
                    class="mr-1"
                    @click="openEditor(i)"
                  >
                    <img :src="item.image" alt />
                  </v-list-item-avatar>

                  <span class="mr-1" @click="openEditor(i)">
                    <v-icon>mdi-square-edit-outline</v-icon>
                  </span>

                  <v-list-item-content @click="openEditor(i)" class="full-width">
                    <v-list-item-title v-text="item[p_key]"></v-list-item-title>
                  </v-list-item-content>

                  <span class="ma-0" @click="removeitem(i)">
                    <v-icon color="error">mdi-delete-empty</v-icon>
                  </span>
                </v-list-item>
                <v-divider v-if="i + 1 < items.length" :key="i"></v-divider>
              </template>
            </v-slide-y-transition>
          </v-list-item-group>
        </perfect-scrollbar>
      </v-list>

      <v-dialog v-model="edit.dialog" max-width="290" @keydown.enter="editItem">
        <v-card class="pa-2">
          <app-upload-multiple-image
            class="pa-0"
            :multiple="false"
            v-if="p_wPicture"
            :maxImage="1"
            :setImages="true"
            @set-images="setEditImage"
            :data-images="edit.image"
          ></app-upload-multiple-image>
          <v-text-field
            class="pa-0"
            v-model="edit[p_key]"
            solo
            :label="'Écrivez vos '+edit.title+' ici'"
            hide-details
          ></v-text-field>
          <v-divider></v-divider>
          <v-card-actions>
            <v-btn color="green darken-1" text @click="edit.dialog = false">Fermer</v-btn>
            <v-btn color="green darken-1" text @click="editItem">Save</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </v-card>
</template>



<script>
import request from "@/common/request";
import { swaltoast, swalDelete } from "@/common/alert";

export default {
  props: {
    p_name: {},
    p_title: {},
    p_wPicture: {
      default: false
    },
    p_action: {},
    p_key: {
      default: "name",
      type: String
    }
  },
  data: () => ({
    index:null,
    showItems: false,
    items: [],
    search: "",
    item: null,
    image: [],
    edit: {
      id: null,
      image: [],
      index: null,
      dialog: false
    }
  }),
  methods: {
    setImage(fileList, type, index, done) {
      if (type == "remove") {
        done();
      }
      this.image = fileList;
    },
    setEditImage(fileList, type, index, done) {
      if (type == "remove") {
        done();
      }
      if (type == "limit") {
        alert(index);
      }
      this.edit.image = fileList;
    },
    openEditor(i) {
      this.edit.id = this.items[i].id;
      this.edit[this.p_key] = this.items[i][this.p_key];
      this.edit.index = i;
      this.edit.image = [];
      if (this.p_wPicture && this.items[i].image != null) {
        this.edit.image.push({
          path: this.items[i].image,
          highlight: 0
        });
      }
      this.edit.dialog = !this.edit.dialog;
    },
    async editItem() {
      try {
        var info = {
          id: this.edit.id,
          [this.p_key]: this.edit[this.p_key],
          image: null
        };
        if (this.p_wPicture && this.edit.image.length == 1) {
          info.image = this.edit.image[0].path;
        }

        var response = await request(this.p_action, "PUT", { data: info });
        this.items[this.edit.index][this.p_key] = info[this.p_key];
        this.items[this.edit.index].image = info.image;

        this.edit.dialog = false;
      } catch (error) {
        console.log(error);
      }
    },
    async removeitem(i) {
      var id = this.items[i].id;

      var accept = await swalDelete();
      if (accept) {
        request(this.p_action, "DELETE", {
          params: { id }
        }).then(res => {
          this.items.splice(i, 1);
          swaltoast("success", "suppression réussie.");
        });
      }
    },
    async create() {
      if (this.item == null) return;

      if (this.p_wPicture && this.image.length > 0) {
        var info = {
          image: this.image[0].path,
          [this.p_key]: this.item
        };
      } else {
        var info = {
          [this.p_key]: this.item
        };
      }

      try {
        var response = await request(this.p_action, "POST", { data: info });
        this.items.unshift(response.data.info);
        this.item = null;
        this.image = []
      } catch (error) {
        console.log(error);
      }
    }
  },
  async created() {
    try {
      var response = await request(this.p_action, "GET", {});
      this.items = response.data.items;
    } catch (error) {
      console.log(error);
    }
  }
};
</script>

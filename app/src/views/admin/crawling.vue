<template>
  <div>
    <v-toolbar class="toolbar-simple">
      <v-toolbar-title left>Crawling</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-icon>mdi-robot</v-icon>
    </v-toolbar>

    <v-container grid-list-md>
      <v-card class="mx-auto mb-3 " >
        <v-toolbar flat dense>
          <v-toolbar-title>
            <span class="subheading">Filter</span>
          </v-toolbar-title>
          <v-spacer></v-spacer>
          {{filter.from}} | ({{filter.count +'/'+ filter.max}})
        </v-toolbar>

        <v-card-text class="pb-0">
          <v-layout justify-space-between mb-4>
            <v-flex text-left>
              <v-layout wrap>
                <v-flex class="xs4">
                  <v-select :items="provider" outlined label="Provider"></v-select>
                </v-flex>
                <v-flex class="xs4">
                  <v-text-field
                    type="number"
                    hide-details
                    outlined
                    label="FROM"
                    class="pa-0"
                    v-model="filter.start"
                  ></v-text-field>
                </v-flex>
                <v-flex class="xs4">
                  <v-slider
                    light
                    hide-details
                    v-model="filter.bpm"
                    track-color="grey"
                    thumb-label="always"
                    always-dirty
                    class="mt-7"
                    min="5"
                    max="60"
                  ></v-slider>
                </v-flex>
              </v-layout>
            </v-flex>
            <v-flex text-right>
              <v-btn class="mr-3" small dark v-if="filter.time != 0">{{filter.time}}</v-btn>
              <v-btn
                :disabled="this.filter.isPlaying"
                class="primary elevation-1"
                fab
                @click="toggle"
              >
                <v-icon large>{{ filter.isPlaying ? 'mdi-pause' : 'mdi-play' }}</v-icon>
              </v-btn>
            </v-flex>
          </v-layout>
        </v-card-text>
      </v-card>
      <v-text-field
        solo
        label="URL"
        class="pa-0"
        v-model="curl"
        append-icon="mdi-target"
        @click:append="getRestoInfo"
      ></v-text-field>

      <v-layout wrap>
        <v-flex xs12 sm4 pa-1 v-for="(product,key) in products" :key="key">
          <div>
            <v-card class="mx-auto" >
              <v-list-item>
                <v-list-item-avatar size="60" color="grey elevation-4 lighten-4" class>
                  <v-img
                    :src="product.image"
                    style="height:60px"
                  ></v-img>
                </v-list-item-avatar>

                <div class="full-width py-1">
                  <v-card-title
                    class="pa-0 subtitle-2 font-weight-black"
                  >{{product.name}}</v-card-title>
                  <v-card-text class="pa-0 caption">Reference {{product.reference}}</v-card-text>
                </div>
              </v-list-item>

              <v-divider></v-divider>

              <v-card-actions class="py-0">
                <v-card-text class="pa-0 caption">{{product.price}}DT</v-card-text>
                <v-spacer></v-spacer>
                <v-btn class="ma-0" text @click="createResto">Add</v-btn>
              </v-card-actions>
            </v-card>
          </div>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>



<script>
import { r_crawlingByUrl } from "@/api/admin/crawling";
import justdata from "@/api/admin/justdata.json";
import { setTimeout, setInterval, clearInterval } from "timers";

const sleep = m => new Promise(r => setTimeout(r, m));

function formatTime(seconds) {
  const h = Math.floor(seconds / 3600);
  const m = Math.floor((seconds % 3600) / 60);
  const s = seconds % 60;
  return [h, m > 9 ? m : h ? "0" + m : m || "0", s > 9 ? s : "0" + s]
    .filter(a => a)
    .join(":");
}

export default {
  data: () => ({
    provider: ["MyTek", "TunisiaNet", "Scoop", "All"],
    curl:
      "https://www.mytek.tn/sacs-sacoches-tunisie/255-sac-a-dos-pour-pc-portable-hp-tunsie.html",
    products: [],
    filter: {
      time: 0,
      bpm: 10,
      interval: null,
      isPlaying: false,
      startCount: 0,
      count: 0,
      start: 0,
      from: 0,
      current: 0,
      max: 1000,
      sleep: 10,
      req_phone: false
    },
    count: 15,
    max: 100
  }),

  methods: {
    toggle() {
      this.filter.isPlaying = !this.filter.isPlaying;
      this.filter.from = this.filter.start;
      this.filter.time = 0;
      var date = new Date(null);
      var count = 0;
      var inter = setInterval(() => {
        this.filter.time = formatTime(count);
        count++;
      }, 1000);
      this.crawlAll();
    },
    async getRestoInfo() {
      this.$store.commit("LAYOUT_SET_LOAD_PROGRESS", true);
      const response = await r_crawlingByUrl(this.curl,"myTek");
      console.log(response);
      response.data.status = true;
      this.products.unshift(response.data.info);
      this.$store.commit("LAYOUT_SET_LOAD_PROGRESS", false);
    },
    async crawlAll() {
      if (this.filter.isPlaying) {
        try {
          this.$store.commit("LAYOUT_SET_LOAD_PROGRESS", true);
          const response = await r_crawlingRestoByUrl(
            justdata.urlset.url[this.filter.from].loc
          );

          this.products.unshift(response.data);
          this.filter.count = this.filter.count + 1;
          this.filter.from = Number(this.filter.from) + 1;

          await sleep(this.filter.sleep * 1000);
          console.log(this.filter.sleep);

          if (this.count < this.max) {
            this.crawlAll();
          }
        } catch (e) {
          if (!e.response) {
            console.log("Please check your internet connection.");
          } else {
            if (typeof e.response.data !== "undefined") {
              if (e.response.data.status == "INVALID_RESTO") {
                this.filter.from = Number(this.filter.from) + 1;
              }
              if (e.response.data.status == "SERVER_PROBLEM") {
                console.log("SERVER_PROBLEM");
                await sleep(this.filter.sleep * 1000);
              }
            }
          }

          this.crawlAll();
        }

        this.$store.commit("LAYOUT_SET_LOAD_PROGRESS", false);
      }
    },
    async createResto() {}
  },
  mounted() {}
};
</script>


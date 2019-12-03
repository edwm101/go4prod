<template>
  <div>
    <v-toolbar class="toolbar-simple">
      <v-toolbar-title>
        <v-icon class="mr-2">mdi-av-timer</v-icon>
        {{$t("schedule")}}
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <span v-if="loadDone">
        Le restaurant est
        <b class="ml-1">{{ isOpen ? $t("open") : $t("close") }}</b>
      </span>
    </v-toolbar>

    <v-container>
      <v-row no-gutters>
        <v-col
          v-for="(operating_time,key) in operating_times"
          :key="key"
          cols="12"
          sm="6"
          md="4"
          xl="3"
        >
          <v-card class="ma-1">
            <v-toolbar
              class="elevation-0 cp grey lighten-4 py-1"
              height="30px"
              @click="operating_time.open_edit = !operating_time.open_edit"
              dense
            >
              <v-icon class="mr-2">mdi-clock</v-icon>
              {{$t(operating_time.day)}}
              <v-spacer></v-spacer>
            </v-toolbar>
            <v-list flat class="py-0">
              <v-list-item class="pa-1 grey lighten-4">
                <v-select
                  :items="time_start"
                  class="mx-1 pa-0"
                  hide-details
                  single-line
                  label="Start"
                  v-model="operating_time.start_time_edit"
                  @change="addItem(key)"
                  @focus="setStartTime(operating_time.end_time_edit)"
                ></v-select>
                <v-select
                  :items="time_end"
                  class="mx-1 pa-0"
                  hide-details
                  single-line
                  label="End"
                  @change="addItem(key)"
                  @focus="setEndTime(operating_time.start_time_edit)"
                  v-model="operating_time.end_time_edit"
                ></v-select>
              </v-list-item>
              <v-divider v-if="operating_time.items.length !=0"></v-divider>
              <v-list-item-group>
                <v-list-item class="pa-1" v-for="(item, i) in operating_time.items" :key="i">
                  <v-select
                    :items="time"
                    class="ma-1"
                    hide-details
                    label="time"
                    v-model="item.start_time"
                    @change="updateItem(item)"
                    :rules="[rules.min_then(item.start_time,item.end_time)]"
                    outlined
                  ></v-select>
                  <v-select
                    :items="time"
                    hide-details
                    class="ma-1"
                    label="time"
                    v-model="item.end_time"
                    :rules="[rules.big_then(item.start_time,item.end_time)]"
                    @change="updateItem(item)"
                    outlined
                  ></v-select>

                  <div style="width:60px">
                    <v-btn small icon class="pa-1 ml-1" @click="deleteItem(key,i)">
                      <v-icon small>mdi-delete</v-icon>
                    </v-btn>
                    <v-switch
                      class="small ma-0 pa-0 float-right"
                      @change="onChangeStatus(item.id)"
                      color="primary"
                      hide-details
                      v-model="item.status"
                    ></v-switch>
                  </div>
                </v-list-item>
              </v-list-item-group>
            </v-list>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import {
  r_deleteOpeningTime,
  r_updateOpeningTime,
  r_addOpeningTime,
  r_getOpeningTimes,
  r_getOpeningTimeStatus,
  r_updateOpeningTimeStatus
} from "@/api/opening-time";
import { swalsmalltoast, swalDelete } from "@/common/alert";
export default {
  data() {
    return {
      rules: {
        min_then: (a, b) => a < b || this.$i18n.t("rule_required"),
        big_then: (b, a) => a > b || this.$i18n.t("rule_required")
      },
      isOpen: false,
      loadDone: false,
      operating_times: [],
      time_end: [],
      time_start: [],
      time: [
        "00:00",
        "00:30",
        "01:00",
        "01:30",
        "02:00",
        "02:30",
        "03:00",
        "03:30",
        "04:00",
        "04:30",
        "05:00",
        "05:30",
        "06:00",
        "06:30",
        "07:00",
        "07:30",
        "08:00",
        "08:30",
        "09:00",
        "09:30",
        "10:00",
        "10:30",
        "11:00",
        "11:30",
        "12:00",
        "13:00",
        "13:30",
        "14:00",
        "14:30",
        "15:00",
        "15:30",
        "16:00",
        "16:30",
        "17:00",
        "17:30",
        "18:00",
        "18:30",
        "19:00",
        "19:30",
        "20:00",
        "20:30",
        "21:00",
        "21:30",
        "22:00",
        "22:30",
        "23:00",
        "23:30",
        "23:59"
      ]
    };
  },
  methods: {
    setEndTime(start_time) {
      if (start_time != undefined) {
        this.time_end = this.time.filter(item => {
          return item > start_time;
        });
      } else {
        this.time_end = this.time;
      }
    },
    setStartTime(end_time) {
      if (end_time != undefined) {
        this.time_start = this.time.filter(item => {
          return item < end_time;
        });
      } else {
        this.time_start = this.time;
      }
    },
    async onChangeStatus(id) {
      await r_updateOpeningTimeStatus(id);
      this.checkIsOpen();
    },
    getOpeningTimes() {
      r_getOpeningTimes().then(response => {
        this.operating_times = response.data.opening_time;
        this.checkIsOpen();
      });
    },
    async checkIsOpen() {
      var res = await r_getOpeningTimeStatus();
      this.isOpen = res.data.open;
      this.loadDone = true;
    },
    deleteItem(key, i) {
      var item = this.operating_times[key].items;
      swalDelete().then(response => {
        if (response) {
          r_deleteOpeningTime(item[i].id).then(() => {
            swalsmalltoast("success", this.$t("alert_delete"));
            item.splice(i, 1);
            this.checkIsOpen();
          });
        }
      });
    },
    updateItem(item) {
      r_updateOpeningTime(item).then(() => {
        swalsmalltoast("success", this.$t("alert_update"));
        this.checkIsOpen();
      });
    },
    async addItem(key) {
      var start_time = this.operating_times[key].start_time_edit;
      var end_time = this.operating_times[key].end_time_edit;

      if (start_time != null && end_time != null) {
        var wday = key + 1;
        var data = { wday, start_time, end_time };
        r_addOpeningTime(data).then(response => {
          this.operating_times[key].items.unshift(response.data.info);
          swalsmalltoast("success", this.$t("alert_add"));
          this.operating_times[key].start_time_edit = null;
          this.operating_times[key].end_time_edit = null;
          this.checkIsOpen();
        });
      }
    }
  },
  created() {
    this.setStartTime();
    this.setEndTime();
    this.getOpeningTimes();
  }
};
</script>

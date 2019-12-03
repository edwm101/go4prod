import Vue from "vue";
import Vuetify from "vuetify/lib";
import fr from "vuetify/es5/locale/fr";
import en from "vuetify/es5/locale/en";

Vue.use(Vuetify);

export default new Vuetify({
  iconfont: "mdi",
  theme: {
    light: true
  },
  lang: {
    locales: {
      fr,en
    },
    current: "fr"
  }
});

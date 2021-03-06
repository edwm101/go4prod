import Vue from 'vue';
import Vuex from 'vuex';
import getters from './getters';
import user from './modules/user';
import layout from './modules/layout';

Vue.use(Vuex);

const store = new Vuex.Store({
  modules: {
    user,
    layout
  },
  getters,
});

export default store;
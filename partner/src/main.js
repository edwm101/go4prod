import Vue from 'vue'
import App from './App.vue';
import vuetify from './plugins/vuetify';
import i18n from './plugins/i18';
import './plugins/scrollbar'
import './plugins/sweetalert2'
import '@/common/function'
import router from '@/router'
import store from '@/store'
import './registerServiceWorker'

require('./assets/style.css')


import {
  setupComponents
} from './components/setup-components';
setupComponents(Vue);

new Vue({
  vuetify,
  i18n,
  router,
  store,
  render: h => h(App)
}).$mount('#app')
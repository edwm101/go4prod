import Vue from 'vue';
import Router from 'vue-router';
import store from '@/store';
import authRouter from '@/router/modules/auth';
import adminRouter from '@/router/modules/admin';

Vue.use(Router);

const router = new Router({
  // mode: 'history',
  routes: [
    authRouter,
    adminRouter,
    {
      path: '*',
      redirect: '/admin'
    },
  ],

});

router.beforeEach((to, from, next) => {
  store.commit("LAYOUT_SET_LOAD_PROGRESS", true);
  document.title = (to.meta.title ? to.meta.title + " - " : "") + "Resto King"
  setTimeout(() => {
    (function smoothscroll() {
      var currentScroll = document.documentElement.scrollTop || document.body.scrollTop;
      if (currentScroll > 0) {
        window.requestAnimationFrame(smoothscroll);
        window.scrollTo(0, currentScroll - (currentScroll / 5));
      }
    })();
  }, 100);
  next()
})

router.afterEach((to, from) => {
  setTimeout(() => {
    store.commit("LAYOUT_SET_LOAD_PROGRESS", false);
  }, 1000);
})


export default router
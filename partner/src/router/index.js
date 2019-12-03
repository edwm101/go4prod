import Vue from "vue";
import Router from "vue-router";
import store from "@/store";
import dashboardRouter from "@/router/modules/dashboard";
import authRouter from "@/router/modules/auth";
import userRouter from "@/router/modules/user";

Vue.use(Router);

const router = new Router({
  // mode: 'history',
  routes: [
    dashboardRouter,
    authRouter,
    userRouter,
    {
      path: "*",
      redirect: "/user"
    }
  ]
});

router.beforeEach((to, from, next) => {
  store.commit("LAYOUT_SET_LOAD_PROGRESS", true);
  document.title =
    (to.meta.title ? to.meta.title + " - " : "") + process.env.VUE_APP_NAME;
  setTimeout(() => {
    (function smoothscroll() {
      var currentScroll =
        document.documentElement.scrollTop || document.body.scrollTop;
      if (currentScroll > 0) {
        window.requestAnimationFrame(smoothscroll);
        window.scrollTo(0, currentScroll - currentScroll / 5);
      }
    })();
  }, 100);
  next();
});

router.afterEach(() => {
  setTimeout(() => {
    store.commit("LAYOUT_SET_LOAD_PROGRESS", false);
  }, 1000);
});

export default router;

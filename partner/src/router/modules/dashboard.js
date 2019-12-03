import store from "@/store";
import DashboardLayout from "@/layout/dashboard";


const dashboardRouter = {
  path: "/dashboard",
  component: DashboardLayout,
  async beforeEnter(to, from, next) {
    await store.dispatch("userValidation");
    if (store.getters.token) {
      store.dispatch("userRestoEnter", store.getters.restaurant_id);
      next();
    } else {
      next({
        name: "signin"
      });
    }
  },
  children: [
    {
      path: "dashboard",
      redirect: "/dashboard/menu"
    }
  ]
};

export default dashboardRouter;

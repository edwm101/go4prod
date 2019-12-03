import store from "@/store";
import AuthLayout from "@/layout/auth";
import signup from "@/views/auth/signup";
import signin from "@/views/auth/signin";

const dashboardRouter = {
  path: "/auth",
  component: AuthLayout,
  async beforeEnter(to, from, next) {
    await store.dispatch("userValidation");
    if (store.getters.token) {
      if (store.getters.role == "user") {
        next({
          name: "restaurants"
        });
      }
    } else {
      next();
    }
  },
  children: [
    {
      path: "signup",
      component: signup,
      name: "signup"
    },
    {
      path: "signin",
      component: signin,
      name: "signin"
    }
  ]
};

export default dashboardRouter;

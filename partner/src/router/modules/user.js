import store from "@/store";

const payment = () => import("@/views/user/payment");
const account = () => import("@/views/user/account");
const providers = () => import("@/views/user/providers");
const userLayout = () => import("@/layout/user");


const userRouter = {
  path: "/user",
  component: userLayout,
  async beforeEnter(to, from, next) {
    await store.dispatch("userValidation");
    if (store.getters.token && store.getters.role == "user") {
      next();
    } else {
      next("/auth/signin");
    }
  },
  children: [
    {
      path: "",
      component: restaurants,
      name: "user"
    },
    {
      path: "restaurants",
      component: restaurants,
      name: "restaurants"
    },
    {
      path: "account",
      component: account,
      name: "account"
    },
    {
      path: "add-restaurant",
      component: addRestaurant,
      name: "add-restaurant"
    },
    {
      path: "payment",
      component: payment,
      name: "payment"
    }
  ]
};

export default userRouter;

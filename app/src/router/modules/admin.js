import store from "@/store";
import adminLayout from "@/layout/admin";
import crawling from "@/views/admin/crawling";
import products from "@/views/admin/products";

const adminRouter = {
  path: "/admin",
  component: adminLayout,
  name: "admin",
  async beforeEnter(to, from, next) {
    await store.dispatch("userValidation");
    if (store.getters.token && store.getters.role == "user") {
      next();
    } else {
      next({ name: "signin" });
    }
  },
  children: [
    {
      path: "",
      component: crawling,
      name: "admin-child"
    },
    {
      path: "products",
      component: products,
      name: "admin-products"
    },

    {
      path: "crawling",
      component: crawling,
      name: "admin-crawling"
    }
  ]
};

export default adminRouter;

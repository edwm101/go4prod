import request from "@/common/request";

export function r_getProducts(params) {
  return request("public/products/find", "GET", {
    params
  });
}

export function r_deleteProduct(id) {
  return request("admin/product/", "DELETE", {
    params: {
      id
    }
  });
}

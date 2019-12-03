import request from "@/common/request";

export function r_getProducts(params) {
  return request("public/products/find", "GET", {
    params
  });
}

import request from "@/common/request";

//Get all the prodcuts with categories (0|1)
export function r_getProviders(is_option) {
  return request("product/all", "GET", {
    params: {
      is_option
    }
  });
}

export function r_ProviderEnter(is_option) {
  return request("product/all", "GET", {
    params: {
      is_option
    }
  });
}

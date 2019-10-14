import request from "@/common/request";

//Set products and categories position in menu
export function r_crawlingByUrl(curl,provider) {
  return request("admin/crawling/product-info", "GET", {
    params: {
      curl,
      provider
    }
  });
}

import request from "@/common/request";

//Set products and categories position in menu
export function r_crawlingByUrl(curl) {
  return request("admin/crawling/product-info", "GET", {
    params: {
      curl
    }
  });
}

//Set products and categories position in menu
export function r_sitemapConvert(url) {
  return request("admin/crawling/sitemap-convert", "GET", {
    params: {
      url
    }
  });
}

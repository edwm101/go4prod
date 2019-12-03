import request from "@/common/request";

//Get all the prodcuts with categories (0|1)
export function r_getProducts(is_option) {
  return request("product/all", "GET", {
    params: {
      is_option
    }
  });
}

//Update the products positons
export function r_updateProductsPosition(items) {
  return request("product/position", "PUT", {
    data: {
      items
    }
  });
}

//Get extra information to edit product
export function r_getProductInfo() {
  return request("product/info", "GET", {});
}

//Get all the product informations
export function r_getProduct(id) {
  return request("product/", "GET", {
    params: {
      id
    }
  });
}

//Add a product
export function r_addProduct(data) {
  return request("product/", "POST", {
    data
  });
}

//Update a product
export function r_updateProduct(data) {
  return request("product", "PUT", {
    data
  });
}

//Delete a product
export function r_deleteProduct(product_id) {
  return request("product", "DELETE", {
    data: {
      product_id
    }
  });
}

//Change the product status (Active/desactive)
export function r_updateProductStatus(product_id) {
  return request("product/status", "PUT", {
    params: {
      product_id
    }
  });
}

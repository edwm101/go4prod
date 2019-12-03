// Core Components
import draggable from "./vuedraggable";
import Header from "./shared/Header";

import ProductEdit from "./modal/ProductEdit"; // @/views/dashborad/menu/property && @/components/product/ProductsDrag

//Form
import AutoCompletChip from "./shared/form/AutoCompletChip"; // @/views/dashborad/menu/product
import AutoCompletMap from "./shared/form/AutoCompletMap"; // 
import UploadMultipleImage from "./shared/form/UploadMultipleImage"; // @/views/dashborad/menu/product &&  @/components/product/Categories
import SimpleAdd from "./shared/form/SimpleAdd"; // @/components/product/Categories &&  // @/components/product/tags

//button
import Fullscreen from "./shared/button/Fullscreen"; // @/layout/dashborad
import ButtonBig from "./shared/button/Big"; // @/view/user/restaurants && @/view/dashborad/category

function setupComponents(Vue) {
  Vue.component("app-draggable", draggable);

  Vue.component("app-header", Header);

  Vue.component("app-upload-multiple-image", UploadMultipleImage);

  Vue.component("app-product-edit", ProductEdit);

  Vue.component("app-simple-add", SimpleAdd);
  Vue.component("app-auto-complet-chip", AutoCompletChip);
  Vue.component("app-auto-complet-map", AutoCompletMap);

  
  Vue.component("app-full-screen", Fullscreen);
  Vue.component("app-button-big", ButtonBig);
}

export { setupComponents };

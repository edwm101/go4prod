// Core Components
import draggable from "./vuedraggable";
import Header from "./shared/Header"




//Form 
import AutoCompletChip from "./shared/form/AutoCompletChip"; // @/views/dashborad/menu/product
import UploadMultipleImage from "./shared/form/UploadMultipleImage"; // @/views/dashborad/menu/product &&  @/components/product/Categories
import SimpleAdd from "./shared/form/SimpleAdd"; // @/components/product/Categories &&  // @/components/product/tags

//button
import Fullscreen from "./shared/button/Fullscreen"; // @/layout/dashborad
import ButtonBig from "./shared/button/Big"; // @/view/user/my-resto && @/view/dashborad/category


function setupComponents(Vue) {
  Vue.component('app-draggable', draggable);

  Vue.component('app-header', Header);

  Vue.component('app-upload-multiple-image', UploadMultipleImage);




  Vue.component('app-simple-add', SimpleAdd);
  Vue.component('app-auto-complet-chip', AutoCompletChip);

  Vue.component('app-full-screen', Fullscreen);
  Vue.component('app-button-big', ButtonBig);

}


export {
  setupComponents
}
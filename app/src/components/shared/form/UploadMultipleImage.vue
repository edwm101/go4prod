<template>
  <div>
    <div class="image-container position-relative text-center" v-if="!chechPath">
      <div class="drag-upload-cover position-absolute" v-if="isDragover" @drop="onDrop">
        <div class="centered full-width text-center text-primary">
          <svg class="icon-drag-drop" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path
              d="M444.5 15C407.7 15 378 44.8 378 81.5s29.8 66.5 66.5 66.5S511 118.2 511 81.5 481.2 15 444.5 15zm29.4 72.4h-23.5l.1 25.9c0 3.2-2.6 5.8-5.8 5.9-3.2 0-5.8-2.6-5.8-5.8l-.1-26h-23.6c-3.2 0-5.8-2.6-5.8-5.8s2.6-5.8 5.8-5.8h23.5l-.1-25.9c0-3.2 2.6-5.8 5.8-5.9 3.2 0 5.8 2.6 5.8 5.8l.1 26h23.6c3.3 0 5.8 2.6 5.8 5.8s-2.6 5.8-5.8 5.8zM199.3 191.3c21.5 0 38.9 17.6 38.9 39.3s-17.4 39.3-38.9 39.3-38.9-17.6-38.9-39.3c0-21.7 17.5-39.3 38.9-39.3zm185.4 201.3H86.3c-6.5 0-11.9-5.3-11.9-11.9v-32.4c0-2.5.7-4.8 2.1-6.9l41.3-58.4c3.7-5.2 10.8-6.5 16.1-3.1l56.4 36.8c4.5 3 10.3 2.5 14.4-1L313 220.1c5.1-4.5 13.1-3.8 17.2 1.7l61.5 79.7c1.6 2 2.5 4.6 2.5 7.2v74.4c0 5.2-4.3 9.5-9.5 9.5zm7.9 117.6h-58.8v-12h58.8v12zm-78.4 0h-58.8v-12h58.8v12zm-78.5 0h-58.8v-12h58.8v12zm-78.4 0H98.4v-12h58.8v12h.1zm-78.5 0H57.7c-14.3 0-27.9-5.4-38.3-15.3l8.3-8.7c8.2 7.8 18.8 12 30.1 12h21.1l-.1 12zm333.6-.1l-.3-12c17.8-.4 33.4-11.5 39.8-28.2l11.2 4.3c-8.1 21.3-28 35.4-50.7 35.9zM6.8 477c-3.2-7.1-4.7-14.7-4.7-22.5v-38.2h12v38.2c0 6.1 1.3 12.1 3.7 17.6l-11 4.9zm459.9-24.1h-12v-58.8h12v58.8zM14.1 396.7h-12v-58.8h12v58.8zm452.6-22.3h-12v-58.8h12v58.8zM14.1 318.3h-12v-58.8h12v58.8zM466.7 296h-12v-58.8h12V296zM14.1 239.8h-12V181h12v58.8zm452.6-22.2h-12v-58.8h12v58.8zM14.1 161.4h-12v-58.8h12v58.8zm2.4-76.1L5.3 81.2C13 59.9 33.4 45.5 56.1 45.5h.2v12h-.2c-17.7 0-33.6 11.2-39.6 27.8zm353.6-27.8h-58.8v-12h58.8v12zm-78.5 0h-58.8v-12h58.8v12zm-78.4 0h-58.8v-12h58.8v12zm-78.5 0H75.9v-12h58.8v12z"
            />
          </svg>
          <h4 class="drop-text-here">
            <b>{{dropText}}</b>
          </h4>
        </div>
      </div>
      <div
        v-else
        class="image-center position-absolute display-flex flex-column justify-content-center align-items-center"
        @dragover.prevent="onDragover"
      >
        <div>
          <svg class="image-icon-drag" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path
              d="M383.6 229l-.5 1.5.7 1.7c-.2-1.1-.2-2.2-.2-3.2zm-119.7-5.4l-.3 1.4.6 1.3c-.2-.8-.3-1.8-.3-2.7zm62.4 3.8l-.2 1 .5 1.1-.3-2.1z"
            />
            <path
              d="M483 326.2l-43.5-100.5c-3.6-8.4-10.3-14.9-18.7-18.4-8.5-3.6-17.8-3.5-26.1.1L391 209c-3.3 1.4-6.1 3.6-8.4 6.3-3.6-8.2-10.2-14.6-18.6-18-8.5-3.4-17.7-3.3-26.1.3-6.1 2.7-10.9 6.8-13.9 12-3.7-8-10.2-14.3-18.4-17.6-8.5-3.4-17.8-3.3-26.1.3l-3.7 1.6c-6.3 2.7-11.2 7.1-14.3 12.4l-20.3-46.9c-4.2-9.8-10.7-16.8-18.7-20.2-8.1-3.5-17.2-3.2-26.5.8l-3.7 1.6c-8 3.5-13.3 9.3-15.5 16.9-2.1 7.3-1 16.2 3.1 25.6l83.4 188.2-64.7-39.8c-11.2-6.8-25.7-4.7-34.4 5.1-11.3 12.5-10.3 31.9 2 43.3l55.8 51.5 50.8 43.4c17.6 16.7 38.2 28.1 59.6 32.9 7.7 1.7 15.5 2.5 23.2 2.5 14.9 0 29.7-3.1 44.2-9.4l27.9-12.1c31.2-13.5 52.8-37.1 62.6-68.4 9.2-29.2 6.6-63-7.3-95.1zM383.6 229c0 1 .1 2.1.2 3.1l-.7-1.7.5-1.4zM281.7 466.6c-.2-.2-.5-.5-.7-.6l-50.4-43.1-55.6-51.5c-7.3-6.7-7.9-18.2-1.2-25.6 4.7-5.3 12.5-6.4 18.5-2.6l65.6 40.2c4.7 2.9 10.4 2.4 14.5-1.3 4.1-3.6 5.3-9.2 3.2-14.2l-83.7-189c-3.2-7.4-3.9-13.4-2.1-18.1 1.7-4.3 5.2-6.5 7.9-7.7l3.7-1.6c12.3-5.3 22.8-.6 28.6 12.9L310.2 350c1.4 3.2 5.1 4.6 8.3 3.3 3.2-1.4 4.7-5.1 3.3-8.3l-48.7-112.5c-2.2-5.2-3-10.8-2-15.4 1.1-5.4 4.5-9.3 9.9-11.7l3.7-1.6c5.3-2.3 11.1-2.3 16.4-.2 5.3 2.2 9.5 6.3 11.8 11.6l43.9 101.5c.7 1.6 1.9 2.7 3.5 3.4 1.6.6 3.3.6 4.8-.1 3.2-1.4 4.7-5.1 3.3-8.3l-32.8-75.9c-8.2-18.9 4.8-25.6 7.5-26.8 10.8-4.7 23.5.4 28.2 11.3l28.9 66.7c1.4 3.2 5.1 4.7 8.3 3.3 3.2-1.4 4.7-5.1 3.3-8.3l-19.4-44.8c-1.3-3-4.9-13.2 3.8-16.9l3.7-1.6c5.2-2.3 11.1-2.3 16.4 0 5.3 2.3 9.6 6.4 11.9 11.8L471.7 331c12.7 29.3 15.1 59.9 6.8 86.3-8.7 27.6-27.9 48.5-55.6 60.5L395 489.9c-38.9 16.9-80.1 8.4-113.3-23.3zm44.6-239.2l.3 2.1-.5-1.1.2-1zm-62.4-3.8l.3 2.7-.6-1.3.3-1.4zM31 217c3.2 0 6-2.6 6-5.7v-40c0-3.2-2.8-5.7-6-5.7s-6 2.6-6 5.7v40c0 3.2 2.8 5.7 6 5.7zm0-66.3c3.2 0 6-2.6 6-5.7v-40c0-3.2-2.8-5.7-6-5.7s-6 2.6-6 5.7v40c0 3.1 2.8 5.7 6 5.7zM148 296h-40c-3.2 0-5.7 2.3-5.7 5.5s2.6 5.5 5.7 5.5h40c3.2 0 5.7-2.3 5.7-5.5s-2.6-5.5-5.7-5.5zM37 237.6c0-3.2-2.8-5.7-6-5.7s-6 2.6-6 5.7v40c0 3.2 2.8 5.7 6 5.7s6-2.6 6-5.7v-40zM31 84.4c3.2 0 6-2.6 6-5.7v-40c0-3.2-2.8-5.7-6-5.7s-6 2.6-6 5.7v40c0 3.1 2.8 5.7 6 5.7zM81.6 296H49.1c-1.7 0-3.4-.6-5-1.3-2.9-1.3-6.3-.1-7.5 2.8-1.3 2.9 0 6.3 2.9 7.5 3 1.3 6.3 2 9.6 2h32.5c3.2 0 5.7-2.3 5.7-5.5s-2.5-5.5-5.7-5.5zm60.6-281c3.2 0 5.7-2.8 5.7-6s-2.6-6-5.7-6h-40c-3.2 0-5.7 2.8-5.7 6s2.6 6 5.7 6h40z"
            />
            <path
              d="M323 122.4c-3.2 0-6 2.6-6 5.7v39.2c0 3.2 2.8 5.7 6 5.7s6-2.6 6-5.7v-39.2c0-3.1-2.8-5.7-6-5.7zm6-60.6c0-3.2-2.8-5.7-6-5.7s-6 2.6-6 5.7v40c0 3.2 2.8 5.7 6 5.7s6-2.6 6-5.7v-40zM301.2 15h3.6c6.8 0 12.2 5.6 12.2 12.4v8.1c0 3.2 2.8 5.7 6 5.7s6-2.6 6-5.7v-8.1C329 14.3 317.9 3 304.8 3h-3.6c-3.2 0-5.7 2.8-5.7 6s2.5 6 5.7 6zm-66.3 0h40c3.2 0 5.7-2.8 5.7-6s-2.6-6-5.7-6h-40c-3.2 0-5.7 2.8-5.7 6s2.5 6 5.7 6zm-60.6 292h40c3.2 0 5.7-2.3 5.7-5.5s-2.6-5.5-5.7-5.5h-40c-3.2 0-5.7 2.3-5.7 5.5s2.5 5.5 5.7 5.5zm-5.8-292h40c3.2 0 5.7-2.8 5.7-6s-2.6-6-5.7-6h-40c-3.2 0-5.7 2.8-5.7 6s2.6 6 5.7 6zM37.1 19.8c1.4 0 2.7-.6 3.8-1.5 2.3-2 5.2-3.2 8.2-3.2h26.8c3.2 0 5.7-2.8 5.7-6s-2.6-6-5.7-6H49.1c-5.9 0-11.5 2.5-15.9 6.5-2.3 2.1-2.5 5.9-.4 8.2 1.1 1.2 2.7 2 4.3 2z"
            />
          </svg>
        </div>
        <div class="text-center">
          <label class="drag-text">{{dragText}}</label>
          <br />
          <a class="browse-text">{{browseText}}</a>
        </div>
        <div class="image-input position-absolute full-width full-height">
          <label :for="idUpload" class="full-width full-height cursor-pointer"></label>
        </div>
      </div>
    </div>

    <div class="image-container position-relative text-center image-list" v-else>
      <div
        class="preview-image full-width position-relative cursor-pointer"
        @click="openGallery(currentIndexImage)"
      >
        <div class="image-overlay position-relative full-width full-height"></div>
        <div class="image-overlay-details full-width">
          <svg class="icon-overlay" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path
              d="M283.9 186.4h-64.6l-.4-71.1c-.1-8.8-7.2-15.9-16-15.9h-.1c-8.8.1-16 7.3-15.9 16.1l.4 70.9h-64.4c-8.8 0-16 7.2-16 16s7.2 16 16 16h64.6l.4 71.1c.1 8.8 7.2 15.9 16 15.9h.1c8.8-.1 16-7.3 15.9-16.1l-.4-70.9h64.4c8.8 0 16-7.2 16-16s-7.1-16-16-16z"
            />
            <path
              d="M511.3 465.3L371.2 325.2c-1-1-2.6-1-3.6 0l-11.5 11.5c31.6-35.9 50.8-82.9 50.8-134.3C406.9 90.3 315.6-1 203.4-1 91.3-1 0 90.3 0 202.4s91.3 203.4 203.4 203.4c51.4 0 98.5-19.2 134.3-50.8l-11.5 11.5c-1 1-1 2.6 0 3.6l140.1 140.1c1 1 2.6 1 3.6 0l41.4-41.4c.9-.9.9-2.5 0-3.5zm-307.9-92.5C109.5 372.8 33 296.4 33 202.4S109.5 32.1 203.4 32.1s170.4 76.4 170.4 170.4-76.4 170.3-170.4 170.3z"
            />
          </svg>
        </div>
        <div class="show-image centered">
          <img class="show-img img-responsive" :src="imagePreview" />
        </div>
      </div>
      <div
        class="image-bottom display-flex position-absolute full-width align-items-center justify-content-between"
        :class="!showPrimary && 'justify-content-end'"
      >
        <div
          class="image-bottom-left display-flex align-items-center"
          v-if="showPrimary && multiple"
        >
          <div class="display-flex align-items-center" v-show="imageDefault">
            <span class="image-primary display-flex align-items-center">
              <svg
                class="image-icon-primary"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 512 512"
              >
                <circle fill="#10BC83" cx="256" cy="256" r="256" />
                <path
                  fill="#FFF"
                  d="M216.7 350.9h-.1c-5.1 0-9.9-2.1-13.4-5.7l-74.2-76c-7.4-7.5-7.2-19.5.4-26.8 7.5-7.4 19.5-7.2 26.8.4L217 305l139.7-138.5c7.5-7.4 19.5-7.4 26.8.1s7.4 19.5-.1 26.8l-153.2 152c-3.7 3.5-8.5 5.5-13.5 5.5z"
                />
              </svg>
              {{primaryText}}
            </span>
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-btn small class="ma-0" icon v-on="on">
                  <v-icon small class="material-icons-outlined">mdi-information-outline</v-icon>
                </v-btn>
              </template>
              <span>{{popupText}}</span>
            </v-tooltip>
          </div>
          <v-btn
            text
            class="text-small mark-text-primary"
            @click="markIsPrimary(currentIndexImage)"
            v-show="!imageDefault"
          >{{markIsPrimaryText}}</v-btn>
        </div>
        <div class="display-flex">
          <v-btn  icon small tag="label" :for="idEdit">
            <v-icon>mdi-content-save-edit-outline</v-icon>
          </v-btn>

          <v-btn small icon @click.prevent="deleteImage(currentIndexImage)">
            <v-icon>mdi-delete-empty</v-icon>
          </v-btn>
        </div>
      </div>
    </div>

    <div class="image-list-container display-flex flex-wrap" v-if="images.length && multiple">
      <div
        class="image-list-item position-relative cursor-pointer"
        :class="image.highlight && 'image-highlight'"
        v-ripple="{ center: true }"
        v-for="(image, index) in images"
        :key="index"
        @click="changeHighlight(index)"
      >
        <div class="centered">
          <img class="show-img img-responsive" :src="image.path" />
        </div>
      </div>
      <div
        class="image-list-item position-relative cursor-pointer display-flex justify-content-center align-items-center"
        v-if="images.length < maxImage"
        v-ripple="{ center: true }"
      >
        <v-icon>mdi-plus</v-icon>
        <div class="input-add-image position-absolute full-width full-height">
          <label :for="idUpload" class="display-block full-width full-height cursor-pointer"></label>
        </div>
      </div>
    </div>
    <div>
      <input
        class="display-none"
        :id="idUpload"
        @change="uploadFieldChange"
        name="images"
        :multiple="multiple"
        :accept="accept"
        type="file"
      />
      <input
        class="display-none"
        :id="idEdit"
        @change="editFieldChange"
        name="image"
        :accept="accept"
        type="file"
      />
    </div>

    <!-- <vue-image-lightbox-carousel
      ref="lightbox"
      :show="showLightbox"
      @close="showLightbox = false"
      :images="images"
      @change="changeHighlight"
      :showCaption="false"
    ></vue-image-lightbox-carousel> -->
  </div>
</template>

<script>
import { forEach, findIndex, orderBy, cloneDeep } from "lodash";
// import VueImageLightboxCarousel from "vue-image-lightbox-carousel";
export default {
  name: "VueUploadMultipleImage",
  props: {
    dragText: {
      type: String,
      default: "Déposez vos photos ici"
    },
    browseText: {
      type: String,
      default: "Sélectionnez"
    },
    primaryText: {
      type: String,
      default: "Par défaut"
    },
    markIsPrimaryText: {
      type: String,
      default: "Définir par défaut"
    },
    popupText: {
      type: String,
      default: "Cette image sera affichée par défaut"
    },
    dropText: {
      type: String,
      default: "Déposez votre fichier ici ..."
    },
    accept: {
      type: String,
      default: "image/gif,image/jpeg,image/png,image/bmp,image/jpg"
    },
    dataImages: {
      type: Array,
      default: () => {
        return [];
      }
    },
    multiple: {
      type: Boolean,
      default: true
    },
    showPrimary: {
      type: Boolean,
      default: true
    },
    maxImage: {
      type: Number,
      default: 5
    }
  },
  data() {
    return {
      idUpload: "edit_" + Math.ceil(Math.random() * 1000000),
      idEdit: "edit_" + Math.ceil(Math.random() * 1000000),
      currentIndexImage: 0,
      images: [],
      isDragover: false,
      showLightbox: false,
      arrLightBox: []
    };
  },
  components: {
    // VueImageLightboxCarousel
  },
  computed: {
    chechPath() {
      if (this.images.length > 0) return true;
      return false;
    },
    imagePreview() {
      let index = findIndex(this.images, { highlight: 1 });
      if (index > -1) {
        return this.images[index].path;
      } else {
        return this.images.length ? this.images[0].path : "";
      }
    },
    imageDefault() {
      if (this.images[this.currentIndexImage]) {
        return this.images[this.currentIndexImage].default;
      }
    }
  },
  methods: {
    onDrop(e) {
      this.isDragover = false;
      e.stopPropagation();
      e.preventDefault();
      let files = e.dataTransfer.files;
      if (!files.length) {
        return false;
      }
      if (!this.isValidNumberOfImages(files.length)) {
        return false;
      }
      forEach(files, (value, index) => {
        this.createImage(value);
        if (!this.multiple) {
          return false;
        }
      });
      if (document.getElementById(this.idUpload)) {
        document.getElementById(this.idUpload).value = [];
      }
    },
    onDragover() {
      this.isDragover = true;
    },
    createImage(file) {
      let reader = new FileReader();
      let formData = new FormData();
      formData.append("file", file);
      reader.onload = e => {
        let dataURI = e.target.result;
        if (dataURI) {
          if (!this.images.length) {
            this.images.push({
              name: file.name,
              path: dataURI,
              highlight: 1,
              default: 1
            });
            this.currentIndexImage = 0;
          } else {
            this.images.push({
              name: file.name,
              path: dataURI,
              highlight: 0,
              default: 0
            });
          }
          this.$emit(
            "set-images",
            this.images,
            "upload",
            this.currentIndexImage,
            () => {}
          );
        }
      };
      reader.readAsDataURL(file);
    },
    editImage(file) {
      let reader = new FileReader();
      let formData = new FormData();
      formData.append("file", file);
      reader.onload = e => {
        let dataURI = e.target.result;
        if (dataURI) {
          if (this.images.length && this.images[this.currentIndexImage]) {
            this.images[this.currentIndexImage].path = dataURI;
            this.images[this.currentIndexImage].name = file.name;
          }
        }
      };
      reader.readAsDataURL(file);
      this.$emit(
        "set-images",
        this.images,
        "edit",
        this.currentIndexImage,
        () => {}
      );
    },
    uploadFieldChange(e) {
      let files = e.target.files || e.dataTransfer.files;
      if (!files.length) {
        return false;
      }
      if (!this.isValidNumberOfImages(files.length + this.images.length)) {
        return false;
      }
      forEach(files, (value, index) => {
        this.createImage(value);
      });
      if (document.getElementById(this.idUpload)) {
        document.getElementById(this.idUpload).value = [];
      }
    },
    editFieldChange(e) {
      let files = e.target.files || e.dataTransfer.files;
      if (!files.length) {
        return false;
      }
      if (!this.isValidNumberOfImages(files.length)) {
        return false;
      }
      forEach(files, (value, index) => {
        this.editImage(value);
      });
      if (document.getElementById(this.idEdit)) {
        document.getElementById(this.idEdit).value = "";
      }
    },
    changeHighlight(currentIndex) {
      let arr = this.images;
      this.images = [];
      arr.map((item, index) => {
        if (currentIndex === index) {
          item.highlight = 1;
        } else {
          item.highlight = 0;
        }
        return item;
      });
      this.currentIndexImage = currentIndex;
      this.images = arr;
    },
    markIsPrimary(currentIndex) {
      this.images.map((item, index) => {
        if (currentIndex === index) {
          item.highlight = 1;
          item.default = 1;
        } else {
          item.highlight = 0;
          item.default = 0;
        }
        return item;
      });

      this.currentIndexImage = 0;
      this.images = orderBy(this.images, "default", "desc");
      this.$emit(
        "set-images",
        this.images,
        "primary",
        this.currentIndexImage,
        () => {}
      );
    },
    deleteImage(currentIndex) {
      this.$emit(
        "set-images",
        this.images,
        "remove",
        this.currentIndexImage,
        () => {
          if (this.images[currentIndex].default === 1) {
            this.images[0].default = 1;
          }
          this.images.splice(currentIndex, 1);
          this.currentIndexImage = 0;
          if (this.images.length) {
            this.images[0].highlight = 1;
          }
        }
      );
    },
    openGallery(index) {
      this.showLightbox = true;
      this.$refs.lightbox.showImage(index);
    },
    onOpenedLightBox(value) {
      if (value) {
        this.showLightbox = true;
      } else {
        this.showLightbox = false;
      }
    },
    isValidNumberOfImages(amount) {
      if (amount > this.maxImage) {
        this.$emit("set-images", this.images, "limit", this.maxImage, () => {});
        return false;
      } else {
        return true;
      }
    }
  },
  watch: {
    dataImages: {
      handler: function(newVal) {
        this.images = newVal;
      },
      deep: true
    }
  },
  mounted() {
    ["dragover", "drop"].forEach(event => {
      window.addEventListener(event, e => {
        e.preventDefault();
        e.stopPropagation();
      });
    });
    document.body.addEventListener("dragleave", event => {
      event.stopPropagation();
      event.preventDefault();
      this.isDragover = false;
    });
  },
  created() {
    this.images = [];
    this.images = cloneDeep(this.dataImages);
  }
};
</script>

<style lang="css" scoped>
.text-small {
  font-size: 11px;
}
.position-relative {
  position: relative;
}
.position-absolute {
  position: absolute;
}
.text-center {
  text-align: center;
}
.text-primary {
  color: #2fa3e6;
}
.display-flex {
  display: flex;
}
.flex-column {
  flex-direction: column;
}
.flex-wrap {
  flex-wrap: wrap;
}
.justify-content-center {
  justify-content: center;
}
.justify-content-between {
  justify-content: space-between;
}
.justify-content-end {
  justify-content: flex-end;
}
.align-items-center {
  align-items: center;
}
.full-width {
  width: 100%;
}
.full-height {
  height: 100%;
}
.cursor-pointer {
  cursor: pointer;
}
.centered {
  left: 50%;
  transform: translate(-50%, -50%);
  top: 50%;
  position: absolute;
  display: block;
}
.image-container {
  border: 0;
  width: 100%;
  height: 180px;
}
.image-center {
  width: 100%;
  height: 100%;
}
.image-icon-drag {
  fill: #c9c8c8;
  height: 50px;
  width: 50px;
}
.drag-text {
  padding-top: 5px;
  color: #777;
  font-weight: 400;
  line-height: 1.5;
}
.browse-text {
  font-size: 86%;
  color: #206ec5;
  text-decoration: none;
}
.image-input {
  overflow: hidden;
  opacity: 0;
  top: 0;
  left: 0;
  bottom: 0;
}
.image-input label {
  display: block;
}
.drag-upload-cover {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: #fcfeff;
  opacity: 0.9;
  z-index: 1;
  margin: 5px;
  border: 2px dashed #268ddd;
}
.drag-upload-cover {
  font-weight: 400;
  font-size: 20px;
}
.icon-drag-drop {
  height: 50px;
  width: 50px;
  fill: #2fa3e6;
}
.drop-text-here {
  margin: 0;
  line-height: 1.5;
}
.display-none {
  display: none;
}

/* list images*/
.preview-image {
  height: 140px;
  padding: 5px;
  border-radius: 15px;
  box-sizing: border-box;
}
.image-overlay {
  background: rgba(0, 0, 0, 0.7);
  z-index: 1;
  border-radius: 5px;
  opacity: 0;
  transition: all 0.3s ease-in-out 0s;
}
.image-overlay-details {
  position: absolute;
  z-index: 2;
  opacity: 0;
  transform: translate(0, -50%);
  top: 50%;
}
.icon-overlay {
  width: 40px;
  height: 40px;
  fill: #fff;
}
.preview-image:hover .image-overlay,
.preview-image:hover .image-overlay-details {
  opacity: 1;
}
.img-responsive {
  display: block;
  max-width: 100%;
  height: auto;
}
.show-img {
  max-height: 126px;
  max-width: 100%;
  display: block;
}
/*image bottom*/
.image-bottom {
  bottom: 0;
  left: 0;
  height: 40px;
  padding: 5px 10px;
  box-sizing: border-box;
}
.image-primary {
  border-radius: 4px;
  background-color: #e3edf7;
  padding: 3px 7px;
  font-size: 11px;
  margin-right: 5px;
}
.image-icon-primary {
  width: 10px;
  height: 10px;
  margin-right: 2px;
}
.image-icon-delete {
  height: 14px;
  width: 14px;
  fill: #222;
}
.image-edit {
  margin-right: 10px;
}
.image-icon-edit {
  height: 14px;
  width: 14px;
  fill: #222;
}
.image-list-container {
  padding: 4px;
}
.image-list-container .image-list-item {
  height: 32px;
  width: 32px;
  border-radius: 4px;
  border: 1px solid #d6d6d6;
  background: white;
  margin: 0.3%;
}
.image-list-container .image-list-item {
  width: 13.68%;
}
.image-list-container .image-list-item .show-img {
  max-height: 38px;
}
.image-list-container .image-highlight {
  border: 1px solid #2fa3e7;
}
.add-image-svg {
  width: 12px;
  height: 12px;
  fill: #222;
}
.input-add-image {
  overflow: hidden;
  opacity: 0;
  top: 0;
  left: 0;
  bottom: 0;
  z-index: 11;
}
.input-add-image label {
  display: block;
}
.image-icon-info {
  width: 14px;
  height: 14px;
  fill: #222;
}
.mark-text-primary {
  color: #034694;
}
</style>


<template>
  <div>
    <v-btn  class="hidden-sm-and-down" icon @click="toggleFullscreen" v-if="isSupported">
      <v-icon v-if="isFullscreen">mdi-fullscreen</v-icon>
      <v-icon v-else>mdi-fullscreen-exit</v-icon>
    </v-btn>
  </div>
</template>

<script>
export default {
  data: () => ({
    isFullscreen: true,
    isSupported : true
  }),
  methods: {
    toggleFullscreen: function(event) {
      if( window.innerHeight == screen.height) 
       this.isFullscreen = true;
      else 
       this.isFullscreen = false;

      var elem = document.documentElement;
      if (
        document.fullscreenEnabled ||
        document.webkitFullscreenEnabled ||
        document.mozFullScreenEnabled ||
        document.msFullscreenEnabled
      ) {
        if (!this.isFullscreen) {
          if (elem.requestFullscreen) {
            elem.requestFullscreen();
            return;
          } else if (elem.webkitRequestFullscreen) {
            elem.webkitRequestFullscreen();
            return;
          } else if (elem.mozRequestFullScreen) {
            elem.mozRequestFullScreen();
            return;
          } else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
            return;
          }
        } else {
          if (document.exitFullscreen) {
            document.exitFullscreen();
            return;
          } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
            return;
          } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
            return;
          } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
            return;
          }
        }
      } else {
         this.isSupported = false;
      }
    }
  }
};
</script>
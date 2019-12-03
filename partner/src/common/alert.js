import Vue from "vue";

export function swalsmalltoast(type, title, time = 4000) {
  return Vue.swal.fire({
    toast: true,
    position: "bottom-start",
    showConfirmButton: false,
    timer: time,
    type: type,
    showCloseButton: true,
    customClass: {
      popup: "swal-" + type
    },
    title
  });
}

export function swaltoast(type, title) {
  return Vue.swal.fire({
    showConfirmButton: false,
    timer: 377000,
    type: type,
    showCloseButton: true,
    customClass: {
      popup: "swal-" + type
    },
    title
  });
}

export async function swalDelete() {
  var bool = false;
  await Vue.swal
    .fire({
      title: "Êtes-vous sûr?",
      text: "Vous ne pourrez pas revenir en arrière!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      cancelButtonText: "Annuler",
      confirmButtonText: "Oui, supprimez-le!"
    })
    .then(result => {
      if (result.value) {
        bool = true;
      } else {
        bool = false;
      }
    });
  return bool;
}

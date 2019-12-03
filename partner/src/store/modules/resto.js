import { r_ProviderEnter } from "@/api/providers";
import jwtService from "@/common/jwt.service";
import router from "@/router";
import { swalsmalltoast } from "@/common/alert";

const resto = {
  state: {
    logo: "",
    name: "",
    open_status: 0,
    slogan: ""
  },
  mutations: {
    RESTO_SET_STATES: (state, payload) => {
      jwtService.saveToken(payload.token);
      Object.assign(state, payload);
    }
  },
  actions: {
    async userRestoEnter({ commit }, payload) {
      try {
        const response = await r_restaurantEnter(payload);
        commit("RESTO_SET_STATES", {
          ...response.data.info,
          token: response.data.token
        });
      } catch (e) {
        if (e.response.data.status == "INVALID_USER") {
          swalsmalltoast("error", "Utilisateur invalide.");
          router.push({ name: "restaurants" });
        }
      }
    }
  }
};

export default resto;

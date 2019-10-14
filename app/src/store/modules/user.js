import {r_userSignIn,r_userSignUp,r_userValidation} from "@/api/auth";

import jwtService from "@/common/jwt.service";
import router from '@/router'
import {
    swalsmalltoast
} from "@/common/alert";



const user = {
    state: {
        first_name: "",
        last_name: "",
        email: "",
        role: "",
        token: "",
        restaurant_id:0,
    },
    mutations: {
        USER_SET_TOKEN: (state, token) => {
            jwtService.saveToken(token);
            Object.assign(state, {
                token
            });
        },
        USER_REMOVE_TOKEN: (state) => {
            jwtService.destroyToken();
            Object.assign(state, {
                token: null
            });
        },
        USER_SET_STATES: (state, payload) => {
            jwtService.saveToken(payload.token)
            Object.assign(state, payload);
        },
    },
    actions: {
        async userSignIn({
            commit
        }, payload) {
            try {
                const response = await r_userSignIn(payload);
                commit('USER_SET_STATES', {
                    ...response.data.info,
                    token: response.data.token
                });
                window.location.reload()
            } catch (e) {
                if (e.response.data.status == "INVALID_EMAIL") {
                    swalsmalltoast("error", "L’e-mail entré ne correspond à aucun compte.");
                }
                if (e.response.data.status == "INVALID_PASSWORD") {
                    swalsmalltoast("error", "Le mot de passe entré est incorrect.");
                }


            }
        },
        async userSignUp({
            commit
        }, payload) {
            try {
                const response = await r_userSignUp(payload);
                commit('USER_SET_STATES', {
                    ...response.data.info,
                    token: response.data.token
                });
                window.location.reload()
            } catch (e) {
                if (e.response.data.status == "EMAIL_EXISTS") {
                    swalsmalltoast("error", "L'adresse email est déjà utilisée par un autre compte.");
                }
            }
        },

        userSignOut({
            commit
        }) {
            try {
                commit('USER_REMOVE_TOKEN', {
                    token: null
                });
                router.push("/auth/signin")
            } catch (e) {
                return false
            }
        },

        async userValidation({
            commit
        }) {
            try {
                const response = await r_userValidation();
                commit('USER_SET_STATES', {
                    ...response.data.info,
                    token: response.data.refresh_token
                });
                return true
            } catch (e) {
                return false
            }
        }
    }
};

export default user;
import request from "@/common/request";


export function r_userSignIn(data) {
    return request(process.env.VUE_APP_API_URL+"/auth/signin", "POST", {
        data
    });
}


export function r_userSignUp(data) {
    return request(process.env.VUE_APP_API_URL+"/auth/signup", "POST", {
        data
    });
}


export function r_userValidation() {
    return request(process.env.VUE_APP_API_URL+"/auth/validation", "GET", {});
}
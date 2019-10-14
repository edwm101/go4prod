import request from "@/common/request";


export function r_userSignIn(data) {
    return request("auth/user/signin", "POST", {
        data
    });
}


export function r_userSignUp(data) {
    return request("auth/user/signup", "POST", {
        data
    });
}


export function r_userValidation() {
    return request("auth/user/validation", "GET", {});
}
import request from "@/common/request";

export function r_userSignIn(data) {
  return request("auth/signin", "POST", {
    data
  });
}

export function r_userSignUp(data) {
  return request("auth/signup", "POST", {
    data
  });
}

export function r_userValidation() {
  return request("auth/validation", "GET", {});
}

import axios from "axios";
import { swaltoast } from "@/common/alert";
import JwtService from "@/common/jwt.service";
import i18n from "@/plugins/i18";

// create an axios instance
const service = axios.create({
  baseURL: process.env.VUE_APP_API_PARTNER_URL,
  timeout: 10000 // request timeout
});

// request interceptor
service.interceptors.request.use(config => {
  config.data = JSON.stringify(config.data);
  config.headers[process.env.VUE_APP_ID_TOKEN_KEY] = JwtService.getToken();
  return config;
});

// Add a response interceptor
service.interceptors.response.use(
  response => {
    return response;
  },
  error => {
    if (typeof error.response.data.status !== "undefined") {
      switch (error.response.data.status) {
        case "ALREADY_EXISTS":
          swaltoast("info", i18n.t("alert_exists"));
          break;
        case "IS_NULL":
          swaltoast("info", i18n.t("alert_is_null"));
          break;
        case "TIME_ERROR_BIG":
          swaltoast("info", i18n.t("alert_time_error_big"));
          break;
        case "TIME_ERROR_INTERVAL":
          swaltoast("info", i18n.t("alert_time_error_interval"));
          break;
        default:
          break;
      }
    }

    // Do something with response error
    return Promise.reject(error);
  }
);

export default function request(url, method, payload) {
  return service({
    url,
    method,
    data: payload.data,
    params: payload.params
  });
}

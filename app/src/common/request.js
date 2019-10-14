import axios from 'axios';

import {
  API_URL,
  ID_TOKEN_KEY
} from '@/common/config';
import JwtService from "@/common/jwt.service";

// create an axios instance
const service = axios.create({
  baseURL: API_URL,
  timeout: 10000, // request timeout
});

// request interceptor
service.interceptors.request.use(
  (config) => {
    config.data = JSON.stringify(config.data);
    config.headers[ID_TOKEN_KEY] = JwtService.getToken();
    return config;
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

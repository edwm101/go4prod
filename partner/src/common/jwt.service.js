 
export const getToken = () => {
  return window.localStorage.getItem(process.env.VUE_APP_ID_TOKEN_KEY);
};

export const saveToken = token => {
  window.localStorage.setItem(process.env.VUE_APP_ID_TOKEN_KEY, token);
};

export const destroyToken = () => {
  window.localStorage.removeItem(process.env.VUE_APP_ID_TOKEN_KEY);
};

export default { getToken, saveToken, destroyToken };
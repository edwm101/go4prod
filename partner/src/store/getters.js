const getters = {
  //USER
  token: state => state.user.token,
  role: state => state.user.role,
  email: state => state.user.email,
  first_name: state => state.user.first_name,
  last_name: state => state.user.last_name,
  full_name: state => state.user.first_name+' '+state.user.last_name,
  restaurant_id : state => state.user.restaurant_id,

  //RESTAURANT
  resto_logo: state => state.resto.logo,
  resto_name: state => state.resto.name,
  resto_open_status: state => state.resto.open_status,
  resto_slogan: state => state.resto.slogan,

  //LAYOUT 
  drawer: state => state.layout.drawer,
  load_progress: state => state.layout.load_progress
};
export default getters;
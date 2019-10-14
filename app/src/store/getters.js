const getters = {
  //USER
  token: state => state.user.token,
  role: state => state.user.role,
  email: state => state.user.email,
  first_name: state => state.user.first_name,
  last_name: state => state.user.last_name,
  full_name: state => state.user.first_name+' '+state.user.last_name,
  restaurant_id : state => state.user.restaurant_id,



  //LAYOUT 
  drawer: state => state.layout.drawer,
  load_progress: state => state.layout.load_progress
};
export default getters;
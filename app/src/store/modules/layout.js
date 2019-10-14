

const user = {
    state: {
        drawer: false,
        load_progress:false
    },
    mutations: {
        LAYOUT_SET_DRAWER: (state) => {
            {
                state.drawer = !state.drawer
            }
        },
        LAYOUT_SET_LOAD_PROGRESS: (state,payload) => {
            {
                state.load_progress = payload
            }
        },
        LAYOUT_INIT_DRAWER: (state, payload) => {
            {
                state.drawer = payload
            }
        }
    },
    actions: {

    }
};

export default user;
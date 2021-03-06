import Vuex from 'vuex';
import Vue from 'vue';
import auth from './modules/auth'
import programs from './modules/programs'

// Load Vuex
Vue.use(Vuex);

// Create store
export default new Vuex.Store({
    modules: {
        auth,
        programs
    }
})


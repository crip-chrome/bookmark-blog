import Vue from 'vue';
import Vuex from 'vuex';
import passport from './modules/passport';

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {passport}
});

export default store;
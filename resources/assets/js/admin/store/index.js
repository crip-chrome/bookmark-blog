import Vue from 'vue';
import Vuex from 'vuex';
import passport from './modules/passport';
import bookmark from './modules/bookmark';

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {passport, bookmark}
});

export default store;
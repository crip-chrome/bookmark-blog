import Vue from 'vue';
import Vuex from 'vuex';
import bookmarks from './modules/bookmarks';

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {bookmarks}
});

export default store;
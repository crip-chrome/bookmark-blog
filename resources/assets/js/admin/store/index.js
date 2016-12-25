import Vuex from 'vuex';
import passport from './modules/passport';

const store = new Vuex.Store({
    modules: {passport}
});

export default store;
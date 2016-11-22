import 'es6-promise/auto';
window.$ = window.jQuery = require('jquery');
window.noop = function () {
};

import Vue from 'vue';
import App from './components/App.vue';
import store from './store';
import router from './router';
import {sync} from 'vuex-router-sync';

sync(store, router);

import VueResource from 'vue-resource';
Vue.use(VueResource);
Vue.http.interceptors.push((request, next) => {
    request.headers.set('X-CSRF-TOKEN', Laravel.csrfToken);

    next();
});
require('bootstrap-sass');

let app = new Vue(Vue.util.extend({
    router,
    store
}, App));

app.$mount('#app');
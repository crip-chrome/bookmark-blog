$.fn.select2.defaults.set('theme', 'bootstrap');

import Vue from 'vue';
import App from './components/App.vue';
import store from './store';
import router from './router';
import {sync} from 'vuex-router-sync';

sync(store, router);

Vue.http.interceptors.push((request, next) => {
    request.headers.set('X-CSRF-TOKEN', Laravel.csrfToken);

    next();
});

let app = new Vue(Vue.util.extend({
    router,
    store
}, App));

app.$mount('#app');
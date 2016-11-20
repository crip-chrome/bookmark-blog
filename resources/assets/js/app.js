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

import VueProgressBar from 'vue-progressbar';
const options = {
    color: '#2873c4',
    failedColor: '#d9534f',
};
Vue.use(VueProgressBar, options);

window._ = require('lodash');
window.$ = window.jQuery = require('jquery');
require('bootstrap-sass');

let app = new Vue(Vue.util.extend({
    router,
    store
}, App));

app.$mount('#app');


//Vue.component('bookmarks-list', require('./components/bookmarks/List.vue'));

//Vue.component('passport-clients', require('./components/passport/Clients.vue'));

//Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue'));

//Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue'));

/*const app = new Vue({
 el: '#app'
 });*/

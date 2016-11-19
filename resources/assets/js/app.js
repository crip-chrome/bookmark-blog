/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

Vue.use(VueResource);
Vue.use(Router);

import App from './components/App.vue'
import { sync } from 'vuex-router-sync';
import router from './router';
import store from './store';

sync(store, router);

const app = new Vue(Vue.util.extend({
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

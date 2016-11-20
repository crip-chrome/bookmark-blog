import Vue from 'vue';
import Router from  'vue-router';

Vue.use(Router);

import BookmarkListView from './components/bookmarks/List.vue';

export default new Router({
    mode: 'history',
    scrollBehavior: () => ({y: 0}),
    routes: [
        {path: '/admin/home/bookmarks/:page(\\d+)?', name: 'bookmarks', component: BookmarkListView},
        {path: '/admin/home/', redirect: '/admin/home/bookmarks/1'}
    ]
})
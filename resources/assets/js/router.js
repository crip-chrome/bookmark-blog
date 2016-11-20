import Vue from 'vue';
import Router from  'vue-router';

Vue.use(Router);

import BookmarkListView from './components/bookmarks/List.vue';
import BookmarkDetailsView from './components/bookmarks/Details.vue';
import BookmarkEditView from './components/bookmarks/Edit.vue';

export default new Router({
    mode: 'history',
    scrollBehavior: () => ({y: 0}),
    routes: [
        {
            path: '/admin/home/bookmarks/:page(\\d+)', name: 'bookmarks', component: BookmarkListView,
            children: [{
                path: 'details/:bookmark(\\d+)',
                name: 'bookmark-details',
                component: BookmarkDetailsView
            }, {
                path: 'edit/:bookmark(\\d+)',
                name: 'bookmark-edit',
                component: BookmarkEditView
            }]
        },
        {path: '/admin/home/', redirect: '/admin/home/bookmarks/1'}
    ]
})
import Vue from 'vue'
import Router from 'vue-router'
import $ from 'jquery'

Vue.use(Router);

import BookmarkList from './components/bookmarks/List.vue'
import BookmarkDetails from './components/bookmarks/Details.vue'
import BookmarkEdit from './components/bookmarks/Edit.vue'
import PersonalAccessTokens from './components/passport/PersonalAccessTokens.vue'
import CreatePersonalAccessToken from './components/passport/CreateToken.vue'
import ShowPersonalAccessToken from './components/passport/ShowToken.vue'
import Categories from './components/categories/List.vue'
import CreateCategory from './components/categories/Create.vue'

const id = $('meta[name="root-bookmark"]').attr('content');

export default new Router({
    mode: 'history',
    scrollBehavior: () => ({y: 0}),
    routes: [
        {
            path: '/admin/bookmarks/:page(\\d+)', name: 'bookmarks', component: BookmarkList,
            children: [
                {path: 'details/:bookmark(\\d+)', name: 'bookmark-details', component: BookmarkDetails},
                {path: 'edit/:bookmark(\\d+)', name: 'bookmark-edit', component: BookmarkEdit}
            ]
        },
        {
            path: '/admin/tokens', name: 'tokens', component: PersonalAccessTokens,
            children: [
                {path: 'create', name: 'token-create', component: CreatePersonalAccessToken},
                {path: 'created', name: 'token-created', component: ShowPersonalAccessToken}
            ]
        },
        {
            path: '/admin/categories', name: 'categories', component: Categories,
            children: [
                {path: 'create', name: 'category-create', component: CreateCategory},
            ]
        },
        {path: '/admin/home/', redirect: `/admin/bookmarks/${id}`}
    ]
})
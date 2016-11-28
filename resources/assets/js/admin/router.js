import Vue from 'vue';
import Router from 'vue-router';
import $ from 'jquery';

Vue.use(Router);

import BookmarkListView from './components/bookmarks/List.vue';
import BookmarkDetailsView from './components/bookmarks/Details.vue';
import BookmarkEditView from './components/bookmarks/Edit.vue';
import PersonalAccessTokensView from './components/passport/PersonalAccessTokens.vue';
import CreatePersonalAccessTokenView from './components/passport/CreateToken.vue';
import ShowPersonalAccessTokenView from './components/passport/ShowToken.vue';

const id = $('meta[name="root-bookmark"]').attr('content');

export default new Router({
    mode: 'history',
    scrollBehavior: () => ({y: 0}),
    routes: [
        {
            path: '/admin/bookmarks/:page(\\d+)', name: 'bookmarks', component: BookmarkListView,
            children: [
                {path: 'details/:bookmark(\\d+)', name: 'bookmark-details', component: BookmarkDetailsView},
                {path: 'edit/:bookmark(\\d+)', name: 'bookmark-edit', component: BookmarkEditView}
            ]
        },
        {
            path: '/admin/tokens', name: 'tokens', component: PersonalAccessTokensView,
            children: [
                {path: 'create', name: 'token-create', component: CreatePersonalAccessTokenView},
                {path: 'created', name: 'token-created', component: ShowPersonalAccessTokenView}
            ]
        },
        {path: '/admin/home/', redirect: `/admin/bookmarks/${id}`}
    ]
})
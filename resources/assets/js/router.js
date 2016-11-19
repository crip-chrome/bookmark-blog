import BookmarkListView from './components/bookmarks/List.vue';

export default new Router({
    mode: 'history',
    scrollBehavior: () => ({ y: 0 }),
    routes: [
        { path: '/bookmark/:page(\\d+)?', component: BookmarkListView },
        { path: '/', redirect: '/bookmark/1' }
    ]
})
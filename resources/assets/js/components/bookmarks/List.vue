<template>
  <div>
    <div class="panel panel-default">

      <div class="panel-heading">My Bookmarks</div>

      <div class="panel-body">

        <breadcrumb></breadcrumb>

        <table class="table table-hover">
          <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>URL</th>
          </tr>
          </thead>
          <tbody>
          <router-link v-for="b in bookmarks" :to="getRoute(b)" tag="tr"
                       class="pointee">
            <td>{{ b.page_id }}</td>
            <td>{{ b.title }}</td>
            <td>{{ b.url }}</td>
          </router-link>
          </tbody>
        </table>

      </div>

    </div>

    <router-view></router-view>

  </div>


</template>

<script>
    import Breadcrumb from './Breadcrumb.vue';

    export default {

        mounted() {
            this.loadPage();
        },

        data() {
            return {
                bookmarks: []
            }
        },

        methods: {

            loadPage(page_id) {
                page_id = page_id || this.$route.params.page;
                this.$http.get(`/private/api/v1/bookmarks/${page_id}`)
                    .then((response) => {
                        this.bookmarks = response.data;
                    });
            },

            getRoute (bookmark) {
                if (bookmark.url) {
                    return {
                        name: 'bookmark-details',
                        params: {
                            page: bookmark.parent_id,
                            bookmark: bookmark.page_id
                        }
                    }
                }

                return {name: 'bookmarks', params: {page: bookmark.page_id}};
            },

        },

        watch: {
            '$route' (to, from) {
                this.loadPage(to.params.page);
            }
        },

        components: {
            breadcrumb: Breadcrumb
        },

    }
</script>

<style>

</style>
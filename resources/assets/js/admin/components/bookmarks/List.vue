<template>
  <div>
    <div class="panel panel-default">

      <div class="panel-heading">My Bookmarks</div>

      <div class="panel-body">

        <div class="alert alert-info" v-if="message">
          <strong>Info!</strong> Please synchronize your bookmarks using
          <a href="https://chrome.google.com/webstore/detail/chrome-bookmark-export-to/kbomgldpcakgidkmilpllehdfncedeak"
             target="_blank">
            Chrome extension</a> to see and manage items in this system.
        </div>

        <breadcrumb :bookmark="bookmark"></breadcrumb>

        <table class="table table-hover">
          <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>URL</th>
            <th></th>
          </tr>
          </thead>
          <tbody>
          <router-link v-for="b in bookmark.children" :to="getRoute(b)" tag="tr"
                       class="pointee">
            <td :class="{ 'text-success': b.visible, 'text-danger': !b.visible }">{{ b.page_id }}</td>
            <td>{{ b.title }}</td>
            <td>{{ b.url }}</td>
            <td>
              <router-link :to="{name: 'bookmark-edit',params: {page: b.parent_id,bookmark: b.page_id}}">
                Edit
              </router-link>
            </td>
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
                bookmark: {},
                message: false
            }
        },

        methods: {

            loadPage(page_id) {
                // ensure that there is no backdrops when using back button
                $('.modal-backdrop').remove();

                page_id = page_id || this.$route.params.page;
                if (page_id != 0) {
                    this.message = false;
                    this.$http.get(`/private/api/v1/bookmarks/${page_id}`)
                        .then((response) => {
                            this.bookmark = response.data;
                        });
                } else {
                    this.message = true
                }
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

  .line-through {
    text-decoration: line-through;
  }

</style>
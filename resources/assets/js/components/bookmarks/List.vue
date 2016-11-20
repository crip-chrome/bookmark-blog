<template>
  <div>
    <div class="panel panel-default">

      <div class="panel-heading">My Bookmarks</div>

      <div class="panel-body">

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
            <td :class="{ 'line-through': !b.visible }">{{ b.page_id }}</td>
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
                bookmark: {}
            }
        },

        methods: {

            loadPage(page_id) {
                page_id = page_id || this.$route.params.page;
                this.$http.get(`/private/api/v1/bookmarks/${page_id}`)
                    .then((response) => {
                        this.bookmark = response.data;
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

  .line-through {
    text-decoration: line-through;
  }

</style>
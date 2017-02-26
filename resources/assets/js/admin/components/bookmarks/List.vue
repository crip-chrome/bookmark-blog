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
            <th>Category</th>
            <th></th>
          </tr>
          </thead>
          <tbody>
          <router-link v-for="bookmark in bookmark.children" :to="getRoute(bookmark)" tag="tr"
                       class="pointee">
            <td @click.prevent="saveVisibility(bookmark)"><input type="checkbox" v-model="bookmark.visible"></td>
            <td>{{ bookmark.title }}</td>
            <td class="col-url">{{ bookmark.url }}</td>
            <td>{{ bookmark.category ? bookmark.category.title : '' }}</td>
            <td>
              <router-link :to="{name: 'bookmark-edit',params: {page: bookmark.parent_id, bookmark: bookmark.page_id}}">
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
    import Vue from 'vue'
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

            saveVisibility (bookmark) {
                Vue.set(bookmark, 'visible', !bookmark.visible);
                const url = `/private/api/v1/bookmarks/${bookmark.page_id}`;
                this.$http
                    .post(url, bookmark)
                    .then(_ => this.loadPage());
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

  .col-url {
    max-width: 430px;
    overflow: hidden;
  }

</style>
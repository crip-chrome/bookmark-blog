<template>
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
        <tr v-for="b in bookmarks">
          <td><router-link :to="{ name: 'bookmarks', params: { page: b.page_id }}">{{ b.page_id }}</router-link></td>
          <td>{{ b.title }}</td>
          <td>{{ b.url }}</td>
        </tr>
        </tbody>
      </table>

    </div>

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
                console.log(`/private/api/v1/bookmarks/${page_id}`);
                this.$http.get(`/private/api/v1/bookmarks/${page_id}`)
                    .then((response) => {
                        this.bookmarks = response.data;
                    });
            },

        },

        watch: {
            '$route' (to, from) {
                this.loadPage(to.params.page);
            }
        },

        components: {
            breadcrumb: Breadcrumb
        }

    }
</script>

<style>

</style>
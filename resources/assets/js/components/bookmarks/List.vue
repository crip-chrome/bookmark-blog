<template>
  <div class="panel panel-default">

    <div class="panel-heading">Bookmarks</div>

    <div class="panel-body">

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
          <td>{{ b.id }}</td>
          <td>{{ b.title }}</td>
          <td>{{ b.url }}</td>
        </tr>
        </tbody>
      </table>

    </div>

  </div>

</template>

<script>
    export default {
        mounted() {
            this.$http.get(`/private/api/v1/bookmarks/1`).then((response) => {
                this.bookmarks = response.data;
            });
        },
        data() {
            return {
                bookmarks: []
            }
        }
    }
</script>

<style>

</style>
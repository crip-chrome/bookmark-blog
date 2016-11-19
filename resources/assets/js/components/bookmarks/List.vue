<template>
  <table class="table table-hover">
    <tr>
      <th>#</th>
      <th>Title</th>
      <th>URL</th>
    </tr>
    <tr v-for="b in bookmarks">
      <td>{{ b.id }}</td>
      <td>{{ b.title }}</td>
      <td>{{ b.url }}</td>
    </tr>
  </table>
</template>

<script>
  export default {
      mounted() {
          this.$http.get(`/api/bookmarks/1`).then((response) => {
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
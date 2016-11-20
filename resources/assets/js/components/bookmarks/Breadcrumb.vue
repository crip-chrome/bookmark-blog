<template>
  <ol class="breadcrumb">
    <li v-for="b in tree">
      <router-link :to="{ name: 'bookmarks', params: { page: b.page_id }}">{{ b.title }}</router-link>
    </li>
    <li class="active">{{ current.title }}</li>
  </ol>
</template>

<script>
    export default {

        mounted() {
            this.loadPage();
        },

        data() {
            return {
                current: {},
                tree: []
            }
        },

        methods: {

            loadPage(page_id) {
                page_id = page_id || this.$route.params.page;
                this.$http.get(`/private/api/v1/bookmarks/${page_id}/tree`)
                    .then((response) => {
                        this.current = response.data.current;
                        this.tree = response.data.tree;
                    });
            },

        },

        watch: {
            '$route' (to, from) {
                this.loadPage(to.params.page);
            }
        },
    }
</script>
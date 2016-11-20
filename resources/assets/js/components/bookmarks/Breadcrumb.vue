<template>
  <ol class="breadcrumb">
    <li v-for="b in tree">
      <router-link :to="{ name: 'bookmarks', params: { page: b.page_id }}">{{ b.title }}</router-link>
    </li>
    <li class="active">{{ bookmark.title }}</li>
  </ol>
</template>

<script>
    export default {

        props: ['bookmark'],

        computed: {
            tree() {
                var tree = [];
                var unshiftParentToTree = b => {
                    if (b.parent) {
                        tree.unshift(b.parent);
                        unshiftParentToTree(b.parent);
                    }
                };

                unshiftParentToTree(this.bookmark);

                return tree;
            }
        }
    }
</script>
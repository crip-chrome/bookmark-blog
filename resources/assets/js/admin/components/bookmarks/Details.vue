<template>
  <div>
    <modal :onHide="hide">
      <div class="modal-header">
        <button class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ bookmark.title }}</h4>
      </div>
      <div class="modal-body">
        <ul class="list-group">
          <li class="list-group-item">Date added: {{ bookmark.date_added }}</li>
          <li class="list-group-item">URL: {{ bookmark.url }}</li>
          <li class="list-group-item">Category: {{ bookmark.category_id ? bookmark.category.title : '' }}</li>
        </ul>
      </div>
    </modal>
  </div>
</template>

<script>
    import Modal from '../bootstrap/Modal.vue';

    export default {

        mounted() {
            let page_id = this.$route.params.bookmark;
            this.$http.get(`/private/api/v1/bookmarks/${page_id}`)
                .then((response) => {
                    this.bookmark = response.data;
                });
        },

        data() {
            return {
                bookmark: {}
            }
        },

        methods: {

            hide() {
                this.$router.push({name: 'bookmarks', params: {page: this.$route.params.page}});
            },

        },

        components: {
            modal: Modal
        },

    }
</script>
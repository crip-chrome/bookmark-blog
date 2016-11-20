<template>
  <div>
    <modal :onHide="hide">
      <div class="modal-header">
        <button class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ bookmark.title }}</h4>
      </div>
      <div class="modal-body">
        <ul class="list-group">
          <li class="list-group-item">Parent id: {{ bookmark.parent_id }}</li>
          <li class="list-group-item">Page id: {{ bookmark.page_id }}</li>
          <li class="list-group-item">Date added: {{ bookmark.date_added }}</li>
          <li class="list-group-item">URL: {{ bookmark.url }}</li>
        </ul>
      </div>
    </modal>
  </div>
</template>

<script>
    import Modal from './../bootstrap/Modal.vue';

    export default {

        mounted() {
            let page_id = this.$route.params.bookmark;
            this.$http.get(`/private/api/v1/bookmarks/${page_id}/details`)
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
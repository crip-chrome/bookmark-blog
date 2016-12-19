<template>
  <div>
    <modal :onHide="hide" id="bookmark-edit-modal">
      <div class="modal-header">
        <button class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ bookmark.title }}</h4>
      </div>

      <form class="modal-body form-horizontal">

        <div class="form-group">
          <label for="title" class="col-sm-2 control-label">Title</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="title" disabled v-model="bookmark.title">
          </div>
        </div>

        <div class="form-group">
          <label for="parent_id" class="col-sm-2 control-label">Parent id</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="parent_id" disabled v-model="bookmark.parent_id">
          </div>
        </div>

        <div class="form-group">
          <label for="page_id" class="col-sm-2 control-label">Page id</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="page_id" disabled v-model="bookmark.page_id">
          </div>
        </div>

        <div class="form-group">
          <label for="date_added" class="col-sm-2 control-label">Date added</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="date_added" disabled v-model="bookmark.date_added">
          </div>
        </div>

        <div class="form-group" v-if="hasUrl">
          <label for="url" class="col-sm-2 control-label">URL</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="url" disabled v-model="bookmark.url">
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <div class="checkbox">
              <label>
                <input type="checkbox" v-model="bookmark.visible"> Public
              </label>
            </div>
          </div>
        </div>

      </form>

      <div class="modal-footer">
        <button data-dismiss="modal" class="btn btn-default">Close</button>
        <button class="btn btn-primary" @click="save">Save changes</button>
      </div>
    </modal>
  </div>
</template>

<script>
    import Modal from '../bootstrap/Modal.vue';

    export default {

        mounted() {
            let page_id = this.$route.params.bookmark;
            this.$http
                .get(`/private/api/v1/bookmarks/${page_id}`)
                .then(response => this.bookmark = response.data);
        },

        computed: {

            hasUrl() {
                return !!this.bookmark.url;
            }

        },

        data() {
            return {
                bookmark: {}
            }
        },

        methods: {

            close() {
                $(this.$el).find('button.close').trigger('click');
            },

            hide() {
                this.$router.push({name: 'bookmarks', params: {page: this.$route.params.page}});
            },

            save() {
                let page_id = this.$route.params.bookmark;
                const url = `/private/api/v1/bookmarks/${page_id}`;
                this.$http
                    .post(url, this.bookmark)
                    .then(r => this.close());
            }

        },

        components: {
            modal: Modal
        },

    }
</script>
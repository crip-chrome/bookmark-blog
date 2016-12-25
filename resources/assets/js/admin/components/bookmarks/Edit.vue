<template>
  <div>
    <modal :onHide="hide" id="bookmark-edit-modal">
      <div class="modal-header">
        <button class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ bookmark.title }}</h4>
      </div>

      <form class="modal-body form-horizontal">

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
          <label for="category" class="col-sm-2 control-label">Category</label>
          <div class="col-sm-10">
            <select2 id="category" :options="category.options" v-model="bookmark.category_id"></select2>
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
    import Vue from 'vue'
    import Modal from './../bootstrap/Modal.vue'
    import Select2 from './../bootstrap/Select2.vue'

    export default {

        mounted() {
            let page_id = this.$route.params.bookmark;

            this.$http
                .get('/private/api/v1/categories/options')
                .then(response => Vue.set(this.category, 'options', response.data));

            // we can show bookmark only after we get options for drop down
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
                bookmark: {},
                category: {
                    options: []
                }
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
            modal: Modal,
            select2: Select2
        },

    }
</script>
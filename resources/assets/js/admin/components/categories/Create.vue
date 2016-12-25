<template>
  <modal :on-hide="onHide" :on-show="onShow">

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>

      <h4 class="modal-title">Create Category</h4>
    </div>

    <div class="modal-body">
      <form class="form-horizontal" role="form" @submit.prevent="store">
        <!-- Name -->
        <div class="form-group" :class="{'has-error': errors.title}">
          <label class="col-md-4 control-label" for="create-category-name">Title</label>

          <div class="col-md-6">
            <input id="create-category-name" type="text" class="form-control" name="title" v-model="form.title">

            <ul class="help-block" v-if="errors.title">
              <li v-for="error in errors.title">{{ error }}</li>
            </ul>
          </div>
        </div>
      </form>
    </div>

    <!-- Modal Actions -->
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <button type="button" class="btn btn-primary" @click="store">Create</button>
    </div>

  </modal>
</template>

<script>
    import Vue from 'vue'
    import Modal from './../bootstrap/Modal.vue'

    export default {

        data() {

            return {
                form: {
                    title: ''
                },
                errors: {}
            }

        },

        methods: {

            onShow() {
                $('#create-category-name').focus();
            },

            onHide() {
                this.$router.push({name: 'categories'});
            },

            store() {
                const url = `/private/api/v1/categories`;
                this.$http
                    .post(url, this.form)
                    .then(
                        r => this.onHide(),
                        ({data}) => {
                            Vue.set(this, 'errors', data);
                        }
                    );
            },

        },

        components: {
            modal: Modal
        }
    }
</script>
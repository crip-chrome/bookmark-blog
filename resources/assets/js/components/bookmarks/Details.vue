<template>
  <div>
    <modal :onHide="hide">
      <div class="modal-header">
        <button class="close" @click="hide">&times;</button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
        body
      </div>
    </modal>
  </div>
</template>

<script>
    import Modal from './../bootstrap/Modal.vue';

    export default {

        data() {
            return {}
        },

        methods: {
            hide() {
                this.$router.push({name: 'bookmarks', params: {page: this.$route.params.page}});
            }
        },

        components: {
            modal: Modal
        },

    }
</script>
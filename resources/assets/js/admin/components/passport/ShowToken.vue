<template>
  <modal :on-hide="hide">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>

      <h4 class="modal-title">
        Personal Access Token
      </h4>
    </div>

    <div class="modal-body">
      <p>
        Here is your new personal access token. This is the only time it will be shown so don't lose it!
        You may now use this token to make API requests.
      </p>

      <pre><code>{{ accessToken }}</code></pre>
    </div>

    <!-- Modal Actions -->
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </modal>
</template>

<script>
    import Modal from '../bootstrap/Modal.vue';

    export default {

        computed: {
            accessToken() {
                return this.$store.state.passport.accessToken;
            }
        },

        methods: {

            hide() {
                this.$router.push({name: 'tokens'});
            }

        },

        components: {
            modal: Modal
        },
    }
</script>
<template>
  <modal :on-hide="hide" :on-show="onShow">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>

      <h4 class="modal-title">Create Token</h4>
    </div>

    <div class="modal-body">
      <!-- Form Errors -->
      <div class="alert alert-danger" v-if="form.errors.length > 0">
        <p><strong>Whoops!</strong> Something went wrong!</p>
        <br>
        <ul>
          <li v-for="error in form.errors">
            {{ error }}
          </li>
        </ul>
      </div>

      <!-- Create Token Form -->
      <form class="form-horizontal" role="form" @submit.prevent="store">
        <!-- Name -->
        <div class="form-group">
          <label class="col-md-4 control-label" for="create-token-name">Name</label>

          <div class="col-md-6">
            <input id="create-token-name" type="text" class="form-control" name="name" v-model="form.name">
          </div>
        </div>

        <!-- Scopes -->
        <div class="form-group" v-if="scopes.length > 0">
          <label class="col-md-4 control-label">Scopes</label>

          <div class="col-md-6">
            <div v-for="scope in scopes">
              <div class="checkbox">
                <label>
                  <input type="checkbox"
                         @click="toggleScope(scope.id)"
                         :checked="scopeIsAssigned(scope.id)">

                  {{ scope.id }}
                </label>
              </div>
            </div>
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
    import Modal from './../bootstrap/Modal.vue';
    import * as mTypes from './../../store/mutations';

    export default {

        computed: {

            scopes() {
                return this.$store.state.passport.scopes;
            }

        },

        data() {
            return {
                form: {
                    name: '',
                    scopes: [],
                    errors: []
                }
            };
        },

        methods: {

            onShow() {
                $('#create-token-name').focus();
            },

            /**
             * Create a new personal access token.
             */
            store() {
                this.form.errors = [];

                this.$http.post('/oauth/personal-access-tokens', this.form)
                    .then(response => {
                        this.form.name = '';
                        this.form.scopes = [];
                        this.form.errors = [];

                        this.$store.commit(mTypes.access_token_change, response.data);
                        this.$router.push({name: 'token-created'});
                    })
                    .catch(response => {
                      /*if (typeof response.data === 'object') {
                       this.form.errors = _.flatten(_.toArray(response.data));
                       } else {*/
                        this.form.errors = ['Something went wrong. Please try again.'];
                      /*}*/
                    });
            },

            /**
             * Toggle the given scope in the list of assigned scopes.
             */
            toggleScope(scope) {
                if (this.scopeIsAssigned(scope)) {
                    this.form.scopes = this.form.scopes.filter(s => s != scope);
                } else {
                    this.form.scopes.push(scope);
                }
            },

            /**
             * Determine if the given scope has been assigned to the token.
             */
            scopeIsAssigned(scope) {
                return !!~this.form.scopes.indexOf(scope);
            },

            hide() {
                this.$router.push({name: 'tokens'});
            }
        },

        components: {
            modal: Modal
        },
    }
</script>
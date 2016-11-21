<template>
  <div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="a">
          <span>Personal Access Tokens</span>

          <a class="action-link" @click="showCreateTokenForm">Create New Token</a>
        </div>
      </div>

      <div class="panel-body">
        <!-- No Tokens Notice -->
        <p class="m-b-none" v-if="tokens.length === 0">
          You have not created any personal access tokens.
        </p>

        <!-- Personal Access Tokens -->
        <table class="table table-borderless m-b-none" v-if="tokens.length > 0">
          <thead>
          <tr>
            <th>Name</th>
            <th></th>
          </tr>
          </thead>

          <tbody>
          <tr v-for="token in tokens">
            <!-- Client Name -->
            <td>{{ token.name }}</td>

            <!-- Delete Button -->
            <td><a class="action-link text-danger" @click="revoke(token)">Delete</a></td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>

    <router-view></router-view>
  </div>
</template>

<script>
    import * as mTypes from './../../store/mutations';

    export default {

        data() {
            return {
                tokens: [],
                scopes: []
            };
        },

        mounted() {
            this.getTokens();
            this.getScopes();
        },

        methods: {

            /**
             * Get all of the personal access tokens for the user.
             */
            getTokens() {
                this.$http.get('/oauth/personal-access-tokens')
                    .then(response => {
                        this.tokens = response.data;
                    });
            },

            /**
             * Get all of the available scopes.
             */
            getScopes() {
                this.$http.get('/oauth/scopes')
                    .then(response => {
                        this.scopes = response.data;
                        this.$store.commit(mTypes.access_scopes_change, this);
                    });
            },

            /**
             * Show the form for creating new tokens.
             */
            showCreateTokenForm() {
                this.$router.push({name: 'token-create'});
            },

            /**
             * Revoke the given token.
             */
            revoke(token) {
                this.$http.delete('/oauth/personal-access-tokens/' + token.id)
                    .then(response => {
                        this.getTokens();
                    });
            }
        },

        watch: {
            '$route' (to, from) {
                // ensure that there is no backdrops when entering this route
                $('.modal-backdrop').remove();
            }
        },
    }
</script>

<style scoped>
  .action-link {
    cursor: pointer;
  }

  .m-b-none {
    margin-bottom: 0;
  }

  .m-b-none td {
    vertical-align: middle;
  }

  .a {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
</style>
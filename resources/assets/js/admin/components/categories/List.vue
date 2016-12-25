<template>
  <div>

    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="a">
          <span>Categories</span>

          <router-link :to="{name: 'category-create'}" class="action-link pull-right">Create New Category</router-link>
        </div>
      </div>

      <div class="panel-body">

        <ul class="list-group">
          <li class="list-group-item" v-for="category in categories">
            <span class="badge">{{category.usages}}</span>
            {{category.title}} <small class="text-muted">by {{category.author.name}}</small>
          </li>
        </ul>

      </div>
    </div>

    <router-view></router-view>

  </div>
</template>

<script>
    export default {

        mounted() {
            this.getCategories();
        },

        data() {
            return {
                categories: []
            }
        },

        methods: {

            getCategories() {
                this.categories = [];
                this.$http.get('/private/api/v1/categories').then(response => {
                    response.data.forEach(category => this.categories.push(category));
                });
            }

        },

        watch: {
            '$route' () {
                // ensure that there is no backdrops when entering this route
                $('.modal-backdrop').remove();
                this.getCategories();
            }
        },

    }
</script>
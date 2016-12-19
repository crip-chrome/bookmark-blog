<template>
  <div>

    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="a">
          <span>Categories</span>

          <a class="action-link pull-right" href @click="showCreateCategoryForm">Create New Category</a>
        </div>
      </div>

      <div class="panel-body">
        Body
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

            showCreateCategoryForm() {
                this.$router.push({name: 'category-create'});
            },

            getCategories() {
                this.categories = [];
                this.$http.get('/private/api/v1/categories').then(response => {
                    response.data.forEach(category => this.categories.push(category));
                });
            }

        }

    }
</script>
<template>
  <ol class="breadcrumb">

    <li class="active">{{ current.title }}</li>
  </ol>
</template>

<script>
    export default {
        data() {
            return {
                current: {},
                tree: []
            }
        }
    }
</script>
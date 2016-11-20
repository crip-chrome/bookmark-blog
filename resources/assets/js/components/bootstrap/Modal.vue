<template>
  <div class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script>
    export default {

        props: ['onHide'],

        mounted() {
            $(this.$el).modal('show');
            $(this.$el).on('hidden.bs.modal', e => {
                this.onHide(e);
            });
        }
    }
</script>
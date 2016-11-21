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

        props: {
            onHide: {type: Function, required: true},
            onShow: {type: Function, required: false, 'default': window.noop}
        },

        mounted() {
            let el = $(this.$el);

            el.modal('show');
            el.on('hidden.bs.modal', e => {
                this.onHide(e);
            });

            el.on('shown.bs.modal', () => {
                this.onShow();
            });
        }
    }
</script>
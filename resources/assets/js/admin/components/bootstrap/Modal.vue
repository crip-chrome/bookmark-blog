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
    import {log, info} from './../../../log'

    export default {

        props: {
            onHide: {type: Function, required: true},
            onShow: {type: Function, required: false, 'default': () => window.noop}
        },

        mounted() {
            info('modal:mounted', {el: this.$el});

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
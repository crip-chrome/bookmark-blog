<template>
  <select class="form-control">
    <slot></slot>
  </select>
</template>

<script>
    import {log, info} from './../../../log'

    export default {

        props: {
            value: {},
            options: {type: Array, 'default': []},
            search: {type: Boolean, 'default': true}
        },

        mounted() {
            info('select2:mounted', {el: this.$el, options: this.options, search: this.search});
            let options = {data: this.options};

            // Allow disable search input
            if (!this.search) {
                options.minimumResultsForSearch = -1;
            }

            const $select = $(this.$el);

            $select
                .select2(options)
                .val(this.value)
                .on('change', () => {
                    this.$emit('input', $select.val())
                });
        },

        watch: {

            value(val) {
                const $select = $(this.$el);
                const old = $select.val();
                if (val != old) {
                    log('select2:value(val)', {val}, {el: this.$el, old});
                    // update value
                    $(this.$el).val(val).trigger('change');
                }
            },

            options (options) {
                log('select2:options(options)', {options}, {el: this.$el});
                // update options
                $(this.$el).select2({data: options});
            }

        },

        destroyed() {
            $(this.$el).off().select2('destroy');
        }

    }
</script>
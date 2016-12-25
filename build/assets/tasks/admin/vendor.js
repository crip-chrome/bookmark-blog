import Vendor from './../../lib/Vendor'

const options = {
    packages: {
        'jquery': {
            debug: './node_modules/jquery/dist/jquery.js',
            release: './node_modules/jquery/dist/jquery.min.js'
        },
        'bootstrap': {
            debug: './node_modules/bootstrap-sass/assets/javascripts/bootstrap.js',
            release: './node_modules/bootstrap-sass/assets/javascripts/bootstrap.js'
        },
        'select-2': {
            debug: './node_modules/select2/dist/js/select2.js',
            release: './node_modules/select2/dist/js/select2.min.js'
        },
        'es6-promise': {
            debug: './node_modules/es6-promise/dist/es6-promise.auto.js',
            release: './node_modules/es6-promise/dist/es6-promise.auto.min.js'
        },
        'vue': {
            debug: './node_modules/vue/dist/vue.js',
            release: './node_modules/vue/dist/vue.min.js'
        },
        'vue-router': {
            debug: './node_modules/vue-router/dist/vue-router.min.js',
            release: './node_modules/vue-router/dist/vue-router.js'
        },
        'vue-resource': {
            debug: './node_modules/vue-resource/dist/vue-resource.js',
            release: './node_modules/vue-resource/dist/vue-resource.min.js'
        },
        'vuex': {
            debug: './node_modules/vuex/dist/vuex.js',
            release: './node_modules/vuex/dist/vuex.min.js'
        }
    },
    output: 'admin.vendor.js'
};

export const scope = new Vendor(options);
export const bundle = scope.bundle;
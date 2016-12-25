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
        }
    },
    output: 'vendor.js'
};

export const scope = new Vendor(options);
export const bundle = scope.bundle;
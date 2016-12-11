import gulp from 'gulp';
import concat from 'gulp-concat'

import Asset from './../../Asset'

const jquery = new Asset('./node_modules/jquery/dist', 'jquery.min.js', 'jquery.js');
const bootstrap = new Asset('./node_modules/bootstrap-sass/assets/javascripts', 'bootstrap.min.js', 'bootstrap.js');

export const scope = {};
export const bundle = () => {
    const vendors = new Array(
        jquery.get(),
        bootstrap.get()
    );

    gulp.src(vendors)
        .pipe(concat('vendor.js'))
        .pipe(gulp.dest('./public/js/public'));
};
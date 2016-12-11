import gulp from 'gulp';
import concat from 'gulp-concat'

import Asset from './../../Asset'

const react = new Asset('./node_modules/react/dist', 'react.min.js', 'react.js');
const react_dom = new Asset('./node_modules/react-dom/dist', 'react-dom.min.js', 'react-dom.js');

export const scope = {};
export const bundle = () => {
    const vendors = new Array(
        react.get(),
        react_dom.get()
    );

    gulp.src(vendors)
        .pipe(concat('vendor.js'))
        .pipe(gulp.dest('./public/js/admin'));
};
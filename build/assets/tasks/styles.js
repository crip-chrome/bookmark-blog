import gulp from 'gulp'
import sass from 'gulp-sass'
import sourcemaps from 'gulp-sourcemaps'
import prefix from 'gulp-autoprefixer'
import rename from 'gulp-rename'

import config from './../config'

const src = `${config.src}/sass/app.scss`;
const dest = gulp.dest(`${config.dist}/css/`);

const buildDebug = _ => {
    return gulp.src(src)
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(prefix({browsers: ['> 5%', 'Explorer >= 8']}))
        .pipe(rename('styles.css'))
        .pipe(sourcemaps.write('.'))
        .pipe(dest);
};

const buildRelease = _ => {
    return gulp.src(src)
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(prefix({browsers: ['> 5%', 'Explorer >= 8']}))
        .pipe(rename('styles.css'))
        .pipe(dest);
};

export const bundle = _ => {
    if (config.release) return buildRelease();

    return buildDebug();
};
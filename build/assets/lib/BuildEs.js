import gulp from 'gulp'
import gutil from 'gulp-util'
import uglify from 'gulp-uglify'
import sourcemaps from 'gulp-sourcemaps'
import source from 'vinyl-source-stream'
import buffer from 'vinyl-buffer'
import watchify from 'watchify'
import browserify from 'browserify'
import vueify from 'vueify'
import babelify from 'babelify'

import config from './../config'

export default class BuildEs {
    constructor(entries, outputName, paths = ['./node_modules'], basedir = '.') {
        this.out = outputName;
        const debug = !config.release;

        this._browsify = browserify({
            basedir, entries, paths, debug
        }).transform(vueify).transform(babelify);

        if (debug) this.setWatch();
    }

    setWatch() {
        this._watch = watchify(this._browsify);
        this._watch.on('update', this.bundle.bind(this));
        this._watch.on('log', gutil.log);
    }

    bundle() {
        if (config.release)
            return this.release();

        return this.debug();
    }

    release() {
        return this._browsify.bundle()
            .pipe(source(this.out))
            .pipe(buffer())
            .pipe(uglify())
            .pipe(gulp.dest(config.distJs));
    }

    debug() {
        return this._watch.bundle()
            .pipe(source(this.out))
            .pipe(buffer())
            .pipe(sourcemaps.init({loadMaps: true}))
            .pipe(uglify())
            .pipe(sourcemaps.write('.'))
            .pipe(gulp.dest(config.distJs));
    }
}
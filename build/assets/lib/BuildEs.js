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

const setWatch = Symbol('set_watch');
const releaseMode = Symbol('release');
const debugMode = Symbol('debug');
const propBrowsify = Symbol('browsify');
const propWatchify = Symbol('watchify');

export default class BuildEs {
    constructor(entries, outputName, paths = ['./node_modules'], basedir = '.') {
        this.out = outputName;
        const debug = !config.release;

        this[propBrowsify] = browserify({
            basedir, entries, paths, debug
        }).transform(vueify).transform(babelify);

        if (debug) this[setWatch]();
    }

    [setWatch]() {
        this[propWatchify] = watchify(this[propBrowsify]);
        this[propWatchify].on('update', this.bundle.bind(this));
        this[propWatchify].on('log', gutil.log);
    }

    bundle() {
        if (config.release)
            return this[releaseMode]();

        return this[debugMode]();
    }

    [releaseMode]() {
        return this[propBrowsify].bundle()
            .pipe(source(this.out))
            .pipe(buffer())
            .pipe(uglify())
            .pipe(gulp.dest(config.distJs));
    }

    [debugMode]() {
        return this[propWatchify].bundle()
            .pipe(source(this.out))
            .pipe(buffer())
            .pipe(sourcemaps.init({loadMaps: true}))
            .pipe(uglify())
            // Avoid writing to external as browsers does not update when new version is there
            .pipe(sourcemaps.write())
            .pipe(gulp.dest(config.distJs));
    }
}
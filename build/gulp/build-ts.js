import gulp from 'gulp'
import gutil from 'gulp-util'
import uglify from 'gulp-uglify'
import sourcemaps from 'gulp-sourcemaps'
import source from 'vinyl-source-stream'
import buffer from 'vinyl-buffer'
import watchify from 'watchify'
import browserify from 'browserify'
import tsify from 'tsify'

import config from './config'

class BuildTs {
    /**
     * Creates an instance of BuildTs.
     * 
     * @param {string} entry
     * @param {string} fileName
     * @param {string} [dist=null]
     * @param {array} [paths=['./node_modules']]
     * @param {string} [basedir='.']
     * 
     * @memberOf BuildTs
     */
    constructor(entry, fileName, dist = null, paths = ['./node_modules'], basedir = '.') {

        //// gutil.log(`build-ts initialised for ${fileName}`);

        this.file = fileName;
        this.dist = dist === null ? config.dist : dist;

        const browserifyConf = {
            basedir, paths, cache: {}, packageCache: {},
            debug: !config.release, entries: [entry]
        };

        this._browserify = browserify(browserifyConf).plugin(tsify);
        this._watchify = config.release ? null : watchify(this._browserify);

        if (!config.release) {
            this._watchify.on('update', this.bundle);
            this._watchify.on('log', gutil.log);
        }
    }

    bundle() {
        gutil.log(`build-ts started for ${this.file}`);
        if (config.release) return this.buildRelease();

        return this.buildDebug();
    }

    buildRelease() {
        gutil.log(`build-ts release mode`);
        return this._browserify
            .bundle()
            .pipe(source(this.file))
            .pipe(buffer())
            .pipe(uglify())
            .pipe(gulp.dest(this.dist));
    }

    buildDebug() {
        gutil.log(`build-ts debug mode`);
        return this._watchify
            .bundle()
            .pipe(source(this.file))
            .pipe(buffer())
            .pipe(sourcemaps.init({ loadMaps: true }))
            .pipe(uglify())
            .pipe(sourcemaps.write('./'))
            .pipe(gulp.dest(this.dist));
    }
}

export default BuildTs;
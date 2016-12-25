import gulp from 'gulp'
import concat from 'gulp-concat'

import config from './../config'

const readPackages = Symbol('read_packages');

export default class Vendor {

    /**
     * Initialize new instance of Vendor
     *
     * @param {object} packages
     * @param {string} output
     */
    constructor({packages, output}) {
        this.mode = config.release ? 'release' : 'debug';
        this.scripts = [];
        this.output = output;

        this[readPackages](packages);
    }

    bundle() {
        return gulp.src(this.scripts)
            .pipe(concat(this.output))
            .pipe(gulp.dest(config.distJs));
    }

    [readPackages](packages) {
        Object.keys(packages).forEach(key => {
            if (typeof packages[key][this.mode] === 'undefined')
                throw new Error(`Mode '${this.mode}' is not defined for '${key}' package building '${this.output}'`);

            this.scripts.push(packages[key][this.mode]);
        });
    }
}
import gulp from 'gulp'
import gutil from 'gulp-util'

import Walk from './lib/Walk'

class Build {
    constructor() {
        this.tasks = [];
        this._init();
    }

    _init() {
        Walk('./build/assets/tasks').forEach(task => {
            const definition = require(`./tasks/${task}`);
            const name = definition.NAME || task.replace('.js', '').split('/').join(':');

            if (!definition || !definition.bundle)
                throw new Error(`Could not define task '${name}' without 'bundle' definition`);

            let bundle = definition.bundle;
            if (definition.scope)
                bundle = bundle.bind(definition.scope);

            gulp.task(name, bundle);
            this.tasks.push(name);
        });
    }

    start() {
        gulp.task('default', this.tasks, _ => {
            gutil.log('assets compiled');
        });
    }
}

const build = new Build();
build.start();
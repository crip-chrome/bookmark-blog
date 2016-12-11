import gulp from 'gulp'

import config from './config'
import Walk from './Walk'

let tasks = new Array();

Walk('./build/gulp/tasks').forEach((task, index) => {

    const definition = require(`./tasks/${task}`);
    const taskName = definition.NAME || task.replace('.js', '').replace('/', ':');

    if (!definition || !definition.bundle || !definition.scope)
        throw new Error(`Could not define task '${taskName}' without 'bundle' and/or 'scope' properties`);

    gulp.task(taskName, definition.bundle.bind(definition.scope));
    tasks.push(taskName);
});

gulp.task('default', tasks, _ => { });
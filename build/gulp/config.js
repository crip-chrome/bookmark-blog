import gutil from 'gulp-util'

export default {
    release: !!(gutil.env.mode && gutil.env.mode === 'release'),
    dist: 'dist'
}; 
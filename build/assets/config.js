import gutil from 'gulp-util'

let config = {
    release: gutil.env.mode && gutil.env.mode === 'release',
    src: './resources/assets',
    dist: './public',
    distJs: ''
};

config.distJs = `${config.dist}/js`;

export default config;
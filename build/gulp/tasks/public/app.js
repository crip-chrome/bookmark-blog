import BuildTs from './../../build-ts'

export const scope = new BuildTs('./resources/assets/ts/index.ts', 'app.js', './public/js/public');
export const bundle = scope.bundle;
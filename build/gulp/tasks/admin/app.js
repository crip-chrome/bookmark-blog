import BuildTs from './../../build-ts'

export const scope = new BuildTs('./resources/assets/ts/admin/index.tsx', 'app.js', './public/js/admin');
export const bundle = scope.bundle;
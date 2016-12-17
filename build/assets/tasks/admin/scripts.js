import BuildEs from './../../lib/BuildEs'
import config from './../../config'

export const scope = new BuildEs(`${config.src}/js/admin/app.js`, 'admin.app.js');
export const bundle = scope.bundle;
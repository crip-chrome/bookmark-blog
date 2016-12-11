import Bootstrap from './bootstrap'

const bootstrap = new Bootstrap();

(<any>window).noop = bootstrap.noop;
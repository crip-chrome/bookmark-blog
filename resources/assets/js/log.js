const executeLog = function (type, args) {
    // Disable for production
    let isDebug = true;

    if (isDebug && console && console[type]) {
        console[type].apply(console, args);
    }
};

export function log(...args) {
    executeLog('log', args);
}

export function info(...args) {
    executeLog('info', args);
}
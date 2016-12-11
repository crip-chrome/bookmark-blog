import config from './config'

class Asset {
    /**
     * Creates an instance of Asset.
     * 
     * @param {string} path
     * @param {string} name
     * @param {string} debugName
     * 
     * @memberOf Asset
     */
    constructor(path, name, debugName) {
        this.path = path;
        this.name = name;
        this.debugName = debugName;
    }

    /**
     * Get file location
     * 
     * @param {boolean} [isRelease=null]
     * @param {string} [name=null]
     * @param {string} [path=null]
     * @returns
     * 
     * @memberOf Asset
     */
    get(isRelease = null, name = null, path = null) {
        if (name == null)
            name = this._name(isRelease);

        path = path ? path : this.path;

        return `${path}/${name}`;
    }

    _name(isRelease = null) {
        if (isRelease !== null)
            return isRelease ? this.name : this.debugName;

        return config.release ? this.name : this.debugName;
    }
}

export default Asset;
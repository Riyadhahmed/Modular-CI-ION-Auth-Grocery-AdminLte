var CacheLibrary = function () {

};

//From: https://github.com/Modernizr/Modernizr/blob/master/feature-detects/storage/localstorage.js
CacheLibrary.prototype.browserSupportsLocalStorage = function () {
    var mod = 'modernizr';
    try {
        localStorage.setItem(mod, mod);
        localStorage.removeItem(mod);
        return true;
    } catch (e) {
        return false;
    }
};

//By default this is empty as some browsers doesn't support localStorage cache
CacheLibrary.prototype.setLocalStorageItem = function (item, value) {

};

//By default this is empty as some browsers doesn't support localStorage cache
CacheLibrary.prototype.removeLocalStorageItem = function (item) {

};

//By default this is empty as some browsers doesn't support localStorage cache
CacheLibrary.prototype.getLocalStorageItem = function (item) {
    return null;
};

CacheLibrary.prototype.setLocalStorageCache = function () {
    if (this.browserSupportsLocalStorage()) {
        //if browser is supporting local storage overrides the empty functions
        this.setLocalStorageItem = function (item, value) {
            localStorage.setItem(item, value);
        };
        this.removeLocalStorageItem = function (item) {
            localStorage.removeItem(item);
        };
        this.getLocalStorageItem = function (item) {
            return localStorage.getItem(item);
        };
    }
};

CacheLibrary = new CacheLibrary();
CacheLibrary.setLocalStorageCache();
import iziToast from 'izitoast';

let installed = false;

class VueIziToast {
    // Static Methods
    static _checkParams(message, title, options, methodName = null) {
        const methodSignature = (!methodName) ? '' : ` Method signature: ${methodName}(message: string, title: string, options: IzitoastOptions)`;

        if (!message || message.constructor !== String) {
            throw `Message must be a string.${methodSignature}`;
        }

        if (title && title.constructor !== String) {
            throw `Title must be a string.${methodSignature}`;
        }

        if (options && options.constructor !== Object) {
            throw `Options must be a object.${methodSignature}`;
        }
    }

    static _checkEventNames(eventName) {
        if (!eventName || eventName.constructor !== String) {
            throw 'Event Name must be a string';
        }
        if (eventName !== 'iziToast-open' && eventName !== 'iziToast-close') {
            throw 'Event Name has only two possible values: iziToast-open or iziToast-close';
        }
    }

    static _initToast(toast) {
        if (toast && toast.constructor !== String) {
            toast = document.querySelector(toast);
        }

        if (!toast || toast.constructor !== HTMLDivElement) {
            toast = document.querySelector('.iziToast');
        }

        return toast;
    }

    static _validateOptions(options) {
        if (options && options.constructor !== Object) {
            throw 'Options must be a object';
        }
    }

    static _validateCallback(callback) {
        if (callback && callback.constructor !== Function) {
            throw 'Callback must be a function';
        }
    }

    // Private Variables
    _izi = iziToast;

    // Public Variables
    accessorName = '$toast';

    constructor(options = {}) {
        // http://izitoast.marcelodolce.com/#Options
        const defaults = {
            zindex: 99999,
            layout: 1,
            balloon: false,
            close: true,
            closeOnEscape: false,
            rtl: false,
            position: 'bottomRight',
            timeout: 5000,
            animateInside: true,
            drag: true,
            pauseOnHover: true,
            resetOnHover: false,
            transitionIn: 'fadeInUp',
            transitionOut: 'fadeOut',
            transitionInMobile: 'fadeInUp',
            transitionOutMobile: 'fadeOutDown',
            buttons: {},
            inputs: {},
            onOpening: function () {
            },
            onOpened: function () {
            },
            onClosing: function () {
            },
            onClosed: function () {
            }
        };
        this.options = {...defaults, ...options};
        this._izi.settings(this.options);
    }

    show(message, title = '', options = {}) {
        VueIziToast._checkParams(message, title, options, 'show');
        this._izi.show(Object.assign({}, options, {message, title}));
    }

    hide(toast = null, options = {}) {
        toast = VueIziToast._initToast(toast);
        VueIziToast._validateOptions(options);
        this._izi.hide(options, toast);
    }

    progress(toast, options = {}, callback = () => {
    }) {
        toast = VueIziToast._initToast(toast);
        VueIziToast._validateOptions(options);
        VueIziToast._validateCallback(callback);
        return this._izi.progress(toast, options, callback);
    }

    destroy() {
        this._izi.destroy();
    }

    info(message, title = '', options = {}) {
        VueIziToast._checkParams(message, title, options, 'info');
        this._izi.info(Object.assign({}, options, {message, title}));
    }

    success(message, title = '', options = {}) {
        VueIziToast._checkParams(message, title, options, 'success');
        this._izi.success(Object.assign({}, options, {message, title}));
    }

    warning(message, title = '', options = {}) {
        VueIziToast._checkParams(message, title, options, 'warning');
        this._izi.warning(Object.assign({}, options, {message, title}));
    }

    error(message, title = '', options = {}) {
        VueIziToast._checkParams(message, title, options, 'error');
        this._izi.error(Object.assign({}, options, {message, title}));
    }

    question(message, title = '', options = {}) {
        VueIziToast._checkParams(message, title, options, 'question');
        this._izi.question(Object.assign({}, options, {message, title}));
    }

    on(eventName, callback) {
        VueIziToast._checkEventNames(eventName);
        VueIziToast._validateCallback(callback);
        document.addEventListener(eventName, callback);
    }

    off(eventName, callback = () => {
    }) {
        VueIziToast._checkEventNames(eventName);
        document.removeEventListener(eventName, callback);
    }

}

export default function install(app, options = {}) {
    const isDev = process.env.NODE_ENV !== 'production';

    if (options && options.constructor !== Object) {
        throw 'Options must be a object';
    }

    if (installed && Vue) {
        if (isDev) {
            console.warn(
                'VueIziToast already installed. Vue.use(VueIziToast) should be called only once.'
            );
        }
        return;
    }

    const newInstance = new VueIziToast(options);

    /**
     * VueIziToast set 'toast' at Vue protoype object.
     */
    app.config.globalProperties[newInstance.accessorName] = newInstance;


    installed = true;

    if (isDev) {
        console.info('VueIziToast is plugged in.');
    }
}

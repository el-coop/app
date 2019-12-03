import httpService from "../HttpService";

export default class Model {
    static async get() {
        try {
            const response = await httpService.get(this.url);
            if (response.status > 199 || response.status < 300) {
                const responseData = response.data;
                return responseData[this.url].map((entity) => {
                    return new this(entity);
                });
            }
        } catch (e) {
        }
        return null;
    }

    static updateCallback() {
    };

    constructor(object = {}) {
        this.status = object.id ? 'saved' : 'new';
        this.id = object.id || Date.now();
        this.dbId = object.id || null;
        this.errors = {};

        this.constructor.fields().forEach((field) => {
            const propertyName = field.name;
            if (object[propertyName]) {
                this[propertyName] = object[propertyName];
                if (field.type === 'date') {
                    this[propertyName] = new Date(this[propertyName]);
                } else if (field.type === 'number') {
                    this[propertyName] = parseFloat(this[propertyName]);
                }
            } else {
                switch (propertyName) {
                    case 'date':
                        this[propertyName] = new Date();
                        break;
                    case 'amount':
                        this[propertyName] = 0;
                        break;
                    default:
                        this[propertyName] = '';
                }
            }
        });
    }

    get url() {
        return this.constructor.url;
    }

    get postData() {
        const result = {};
        this.constructor.fields().forEach((field) => {
            const propertyName = field.name;
            result[propertyName] = this[propertyName];
        });
        return result;
    }


    async save() {
        this.status = 'uploading';
        let response;
        try {
            this.errors = {};
            let method = 'post';
            let url = this.url;
            if (this.dbId) {
                method = 'patch';
                url += `/${this.dbId}`;
            }

            response = await httpService[method](url, this.postData);

            if (response.status > 199 && response.status < 300) {
                this.status = 'saved';
                this.dbId = response.data.id;
                this.id = response.data.id;
                this.constructor.updateCallback(this, response);
                return response;
            }

            this.errors = response.data.errors || [];
        } catch (error) {
            response = error.response;
        }

        this.status = 'error';
        return response;
    }

    async delete() {
        this.status = 'deleting';

        try {
            const response = await httpService.delete(`${this.url}/${this.dbId}`);
            if (response.status > 199 && response.status < 300) {
                return true;
            }
        } catch (error) {
        }
        this.status = 'saved';
        return false;
    }
}

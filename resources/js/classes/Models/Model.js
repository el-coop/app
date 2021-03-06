import httpService from "../HttpService";

function serializeData(formData, name, value) {
    if (value !== null) {
        if (value instanceof Date) {
            formData.append(name, value.toUTCString());
        } else if (value instanceof File) {
            formData.append(name, value);
        } else if (Array.isArray(value) || (typeof value === 'object')) {
            for (let val in value) {
                serializeData(formData, `${name}[${val}]`, value[val]);
            }
        } else {
            formData.append(name, value);
        }
    }
};

export default class Model {
    static async get() {
        try {
            const response = await httpService.get(this.endpoint);
            if (response.status > 199 || response.status < 300) {
                const responseData = response.data;
                return responseData[this.endpoint].map((entry) => {
                    return new this(entry);
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
                switch (field.type) {
                    case 'date':
                        this[propertyName] = new Date(this[propertyName]);
                        break;
                    case 'number':
                        this[propertyName] = parseFloat(this[propertyName]);
                        break;
                    case 'array':
                        this[propertyName] = JSON.parse(this[propertyName]).map((entry) => {
                            entry.checked = true;
                            return entry;
                        });
                        break;
                }
            } else {
                switch (field.type) {
                    case 'date':
                        this[propertyName] = new Date();
                        break;
                    case 'array':
                        this[propertyName] = [];
                        break;
                    case 'number':
                        this[propertyName] = 0;
                        break;
                    default:
                        this[propertyName] = '';
                }
            }
        });
    }

    get endpoint() {
        return this.constructor.endpoint;
    }

    get postData() {
        const formData = new FormData;

        this.constructor.fields().forEach((field) => {
            const propertyName = field.name;
            const value = this[propertyName] || null;
            serializeData(formData, propertyName, value);
        });
        return formData;
    }


    async save() {
        this.status = 'uploading';
        let response;
        try {
            const data = this.postData;
            this.errors = {};
            let endpoint = this.endpoint;
            if (this.dbId) {
                data.append('_method', 'patch');
                endpoint += `/${this.dbId}`;
            }

            response = await httpService.post(endpoint, data);

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
            const response = await httpService.delete(`${this.endpoint}/${this.dbId}`);
            if (response.status > 199 && response.status < 300) {
                return true;
            }
        } catch (error) {
        }
        this.status = 'saved';
        return false;
    }
}

import httpService from "../HttpService";
import {reactive} from "vue";

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
                return reactive(responseData[this.endpoint].map((entry) => {
                    return new this(entry);
                }));
            }
        } catch (e) {
        }
        return null;
    }

    static updateCallback() {
    };

    constructor(object = {}) {
        this.status = object.status || (object.id ? 'saved' : 'new');
        this.id = object.id || Date.now();
        if (object.fromLocalStorage) {
            this.dbId = object.dbId;
        } else {
            this.dbId = object.id || null;
        }
        this.errors = object.errors || {};


        this.constructFields(object);
        this.constructRelationships(object);
    }

    constructFields(object) {
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
                        if (!Array.isArray(this[propertyName])) {
                            this[propertyName] = JSON.parse(this[propertyName]).map((entry) => {
                                entry.checked = true;
                                return entry;
                            });
                        }
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


    constructRelationships(object) {
        if (this.constructor.relationships) {
            for (let relationship in this.constructor.relationships) {
                this[relationship] = [];
                if (object[relationship]) {
                    object[relationship].forEach((item) => {
                        const relationshipClass = this.constructor.relationships[relationship]
                        item[this.constructor.name.toLowerCase()] = this.dbId;
                        this[relationship].push(new relationshipClass(item));
                    });
                }
            }
        }
    }

    get endpoint() {
        return this.constructor.endpoint;
    }

    get postData() {
        const formData = new FormData;

        this.constructor.fields().forEach((field) => {
            const propertyName = field.name;
            const value = this[propertyName] ?? null;
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
            let method = 'post';
            if (this.dbId) {
                method = 'patch';
                endpoint += `/${this.dbId}`;
            }

            response = await httpService[method](endpoint, data);

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

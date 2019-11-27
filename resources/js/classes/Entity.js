import httpService from './HttpService';

let entityList = null;

export default class Entity {
    static fields() {
        return [{
            name: 'name',
            label: 'Entity Name',
        }]
    }

    static async list() {
        if (!entityList) {
            entityList = await Entity.get();
        }

        return entityList;
    }

    static async get() {
        try {
            const response = await httpService.get(`/entities`);
            if (response.status > 199 || response.status < 300) {
                const responseData = response.data;
                return responseData.entities.map((entity) => {
                    return new Entity(entity);
                });
            }
        } catch (e) {
        }
        return [];
    }

    constructor(object = {}) {
        this.status = object.id ? 'saved' : 'new';
        this.id = object.id || Date.now();
        this.dbId = object.id || null;
        this.errors = {};

        Entity.fields().forEach((field) => {
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
}

import Model from "./Model";

export default class Project extends Model {

    static get url() {
        return 'projects';
    }

    static fields() {
        return [{
            name: 'name',
            label: 'Project Name',
        }]
    }

    static updateCallback(object, response) {
        object.token = response.data.token;
    }

    constructor(object) {
        super(object);
        this.entity = object.entity;
        this.token = object.token || '';
    }

    get url() {
        return `entities/${this.entity}/projects`;
    }
}

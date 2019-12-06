import Model from "./Model";
import ProjectError from "./ProjectError";
import httpService from "../HttpService";

export default class Project extends Model {

    static get endpoint() {
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
        this.projectErrors = [];
    }

    get endpoint() {
        return `entities/${this.entity}/projects`;
    }

    async loadErrors() {
        const response = await httpService.get(`projects/${this.id}/errors`);
        if (response.status > 199 && response.status < 300) {
            this.projectErrors = response.data.map((error) => {
                error.project_id = this.id;
                return new ProjectError(error);
            });
        } else if (response.status !== 401 && response.data.message !== 'Unauthenticated.') {
            return false
        }
        return true;
    }
}

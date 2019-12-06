import Model from "./Model";

export default class ProjectError extends Model {

    static get endpoint() {
        return 'errors';
    }

    static fields() {
        return [{
            name: 'url',
            label: 'URL',
        }, {
            name: 'message',
            label: 'Message',
        }]
    }

    get endpoint() {
        return `/projects/${this.project_id}/errors`;
    }

    constructor(object) {
        super(object);
        this.project_id = object.project_id;
        this.entries = object.entries;
        this.created_at = new Date(object.created_at).toLocaleString('en-GB');
    }
}

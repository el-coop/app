import Model from "./Model";

export default class ProjectError extends Model {

    static get endpoint() {
        return 'errors';
    }

    static fields() {
        return [{
            name: 'url',
            label: 'URL',
        },{
            name: 'message',
            label: 'Message',
        }]
    }

    constructor(object) {
        super(object);
        this.entries = object.entries;
        this.created_at = new Date(object.created_at).toLocaleString('en-GB');
    }
}

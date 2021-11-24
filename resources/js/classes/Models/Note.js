import Model from "./Model";

let noteList = null;

export default class Note extends Model {

    static get endpoint() {
        return 'notes';
    }

    static fields() {
        return [{
            name: 'title',
            label: 'Title',
        },{
            name: 'content',
            label: 'Content',
            component: 'TextareaField'
        }]
    }

    constructor(object) {
        super(object);
        this.entity = object.entity;
        this.updated_at = object.updated_at;
        this.x = object.x;
        this.y = object.y;
    }

    get endpoint() {
        return `entities/${this.entity}/notes`;
    }

    static async list() {
        if (!noteList) {
            noteList = await Note.get();
        }
        return noteList || [];
    }
}

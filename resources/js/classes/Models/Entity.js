import Model from "./Model";
import Project from "./Project";
import Note from "./Note";

function findById(array, id) {
    return array.findIndex((object) => {
        return object.id === id;
    });
}

let entityList = null;

export default class Entity extends Model {
    static fields() {
        return [{
            name: 'name',
            label: 'Entity Name',
        }]
    }

    static get endpoint() {
        return 'entities';
    }

    static get relationships() {
        return {
            projects: Project,
            notes: Note
        }
    }

    static async list() {
        if (!entityList) {
            entityList = await Entity.get();
        }

        return entityList || [];
    }

    static updateCallback(entity) {
        const index = findById(entityList, entity.id);
        if (index > -1) {
            entityList[index] = entity;
            return;
        }

        entityList.push(entity);
    }

    constructor(object = {}) {
        super(object);
    }

    get addresses() {
        const result = {};
        this.notes.filter((note) => {
            return note.title.toLowerCase().indexOf('address') > -1;
        }).forEach((note) => {
            result[note.content] = note.content;
        });

        return result;
    }
}

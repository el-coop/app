import Model from "./Model";
import Project from "./Project";

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

    static get url() {
        return 'entities';
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
        this.projects = [];
        if(object.projects){
            object.projects.forEach((project) => {
                project.entity = this.dbId;
                this.projects.push(new Project(project));
            })
        }
    }
}

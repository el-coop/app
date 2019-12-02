import Model from "./Model";

export default class Project extends Model {
    static fields() {
        return [{
            name: 'name',
            label: 'Project Name',
        }]
    }

    static get url() {
        return 'projects';
    }
}

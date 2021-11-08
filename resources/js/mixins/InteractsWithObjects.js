export default {
    methods: {
        getById(array, id) {
            return array.find((object) => {
                return object.id === id;
            });
        },

        findById(array, id) {
            return array.findIndex((object) => {
                return object.id === id;
            });
        },

        removeById(array, id) {
            const index = this.findById(array, id);
            if (index > -1) {
                array = array.splice(index, 1);
            }
        },

        updateById(array, id, newValue) {
            const index = this.findById(array, id);
            if (index > -1) {
                return array[index] = newValue;

            }

            array.push(newValue);
        }
    }
};

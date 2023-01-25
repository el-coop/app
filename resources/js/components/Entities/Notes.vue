<template>
    <div class="card">
        <div class="card__header">
            <h5>
                Double click in the notes area to add note
            </h5>
            <div class="notes__filter">
                <TextField v-model="filter" class="field--marginless" :options="{
                    placeholder: 'Filter'
                }"/>
                <SelectField v-model="sort" :options="{
                    options: {
                        'free': 'Free sort',
                        'auto': 'Auto sort'
                    },
                }"/>
            </div>
        </div>
        <div class="notes" @dblclick.prevent="newNote" ref="notes">
            <Note v-for="(note, index) in filteredNotes" :key="`note_${note.id}`" class="notes__note" :note="note"
                  :auto-sort="sort === 'auto'" @update:note="updateNote" @destroy:note="destroyNote"/>
        </div>
    </div>
</template>

<script>
import NoteModel from "../../classes/Models/Note";
import Note from "./Note.vue";
import SelectField from "../../global/Fields/SelectField.vue";
import TextField from "../../global/Fields/TextField.vue";
import InteractsWithObjects from "../../mixins/InteractsWithObjects";

export default {
    name: "Notes",
    components: {SelectField, Note, TextField},
    mixins: [InteractsWithObjects],

    props: {
        entity: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            notes: this.entity.notes,
            sort: localStorage.getItem('notes-sort') || 'free',
            filter: ''
        }
    },
    computed: {
        filteredNotes() {
            if (!this.filter) {
                return this.notes;
            }
            return this.notes.filter((note) => {
                return (
                    note.title.toLowerCase().indexOf(this.filter.toLowerCase()) > -1 ||
                    note.content.toLowerCase().indexOf(this.filter.toLowerCase()) > -1
                );
            });
        }
    },
    methods: {
        newNote(event) {
            const notes = this.$refs.notes;
            const boundingRect = notes.getBoundingClientRect();
            this.notes.push(new NoteModel({
                x: Math.round(event.clientX - boundingRect.left + this.$refs.notes.scrollLeft),
                y: Math.round(event.clientY - boundingRect.top + this.$refs.notes.scrollTop),
                entity: this.entity.id,
            }));
        },

        async updateNote(note) {
            const response = await note.save();

            if (note.status === 'saved') {
                this.$toast.success(' ', 'Note saved');
            } else {
                if (response.status !== 401 && response.data.message !== 'Unauthenticated.') {
                    this.$toast.error('Please try again', 'Note save error');
                }
            }
        },

        async destroyNote(note) {
            let destroyed = true;
            if (note.dbId) {
                destroyed = await note.delete();
            }

            if (destroyed) {
                this.removeById(this.notes, note.id);
                this.$toast.success(' ', 'Note deleted');
                return;
            }
            this.$toast.error('Please try again', 'Note delete error');

        }

    },

    watch: {
        sort(value) {
            localStorage.setItem('notes-sort', value);
        }
    }
}
</script>

<style scoped lang="scss">
.notes {
    height: 75vh;
    position: relative;
    overflow: auto;
    border: 1px dashed var(--border-color);
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    justify-content: flex-start;
    align-content: flex-start;
    gap: 10px;
    flex-direction: row;

    &__filter {
        display: flex;
        align-items: center;
    }
}
</style>

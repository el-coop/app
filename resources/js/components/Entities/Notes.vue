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
        <div class="notes" @dblclick.prevent="newNote">
            <Note v-for="(note, index) in filteredNotes" :key="`note_${index}`" class="notes__note" :note="note"
                  :auto-sort="sort === 'auto'"/>
        </div>
    </div>
</template>

<script>
import NoteModel from "../../classes/Models/Note";
import Note from "./Note";
import SelectField from "../../global/Fields/SelectField";
import TextField from "../../global/Fields/TextField";

export default {
    name: "Notes",
    components: {SelectField, Note, TextField},
    props: {
        entity: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            notes: [],
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
            this.notes.push(new NoteModel({
                x: event.layerX,
                y: event.layerY,
                updated_at: new Date(),
                entity: this.entity.id,
                title: (Math.random() + 1).toString(36).substring(7),
                content: (Math.random() + 1).toString(36).substring(7),
            }));
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
    flex-direction: column;

    &__filter {
        display: flex;
        align-items: center;
    }
}
</style>

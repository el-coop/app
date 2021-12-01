<template>
    <div :style="style" class="card note"
         :class="{'loading': loading, 'note__sort-auto': autoSort, 'card--is-danger': note.status === 'error'}"
         @dblclick.stop
         :draggable="!autoSort && !editing"
         @dragstart="dragStart" @dragend="dragEnd">
        <p class="note__header">
                <span v-text="note.updated_at ? `${note.updated_at.toLocaleString('en-GB',{
                    timeStyle: 'short',
                    dateStyle: 'short',
                })}` : ''"/>
            <button class="button is-small is-danger" :disabled="loading" @click="destroyNote">
                <FontAwesomeIcon icon="trash" fixed-width/>
            </button>
        </p>
        <div class="note__overview" v-if="note.title && note.status !== 'error'">
            <p v-text="note.title" class="note__overview-title"/>
            <pre v-text="note.content" class="note__overview-body" :class="{'is-loading': loading}"/>
        </div>
        <EditForm v-if="!loading" :entry="note" :fields="fields"
                  :class="{'note__body': note.title  && note.status !== 'error'}"
                  @update:entry="updateNote" @focus:start="editing = true" @focus:end="editing = false"/>
    </div>
</template>

<script>
import EditForm from "../../global/Forms/EditForm";
import Note from "../../classes/Models/Note";

export default {
    name: "Note",
    components: {EditForm},
    props: {
        note: {
            type: Object,
            required: true,
        },
        autoSort: {
            type: Boolean,
            default: false

        },
    },
    mounted() {
        if (this.note.status === 'new') {
            this.$el.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'nearest'
            });
        }
    },

    data() {
        return {
            fields: Note.fields(),
            startDragX: null,
            startDragY: null,
            editing: false
        }
    },

    methods: {
        dragStart(event) {

            this.startDragX = event.screenX;
            this.startDragY = event.screenY;
        },
        dragEnd(event) {
            const xDifference = event.screenX - this.startDragX;
            const yDifference = event.screenY - this.startDragY;
            this.note.x += xDifference;
            this.note.y += yDifference;
            if (this.note.x < 0) {
                this.note.x = 0;
            }
            if (this.note.y < 0) {
                this.note.y = 0;
            }

            this.startDragX = null;
            this.startDragY = null;
        },
        updateNote(updatedNote) {
            this.note.title = updatedNote.title;
            this.note.content = updatedNote.content;
            this.$emit('update:note', this.note)
        },

        destroyNote() {
            this.$emit('destroy:note', this.note);
        }

    },

    computed: {
        style() {
            return {
                left: `${this.note.x}px`,
                top: `${this.note.y}px`,
            }
        },
        loading() {
            return this.note.status === 'uploading' || this.note.status === 'deleting'
        },
    }
}
</script>

<style lang="scss" scoped>
.note {
    position: absolute;
    width: 300px;

    &__sort-auto {
        position: static;
    }

    &__body {
        display: none;
    }

    &__header {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }

    &__overview {
        position: relative;

        &-date {
            margin-bottom: .2rem;
            font-weight: var(--weight-light);
        }

        &-title {
            padding-bottom: .5rem;
            margin-bottom: .5rem;
            border-bottom: 1px solid var(--border-color);
            font-weight: var(--weight-bold);
        }

        &-body {
            min-height: 2em;
            max-height: 5.25em;
            white-space: pre-wrap;
            overflow: auto;
        }
    }

    &:not(.loading) {
        &:hover, &:focus-within {
            z-index: 100;

            .note__overview {
                display: none;
            }

            .note__body {
                display: block;
            }
        }
    }
}
</style>

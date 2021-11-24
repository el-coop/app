<template>
    <div :style="style" class="card note" :class="{'loading': loading, 'note__sort-auto': autoSort}" @click.right.stop
         :draggable="!autoSort"
         @dragstart="dragStart" @dragend="dragEnd">
        <div class="note__overview" v-if="note.title">
            <p v-text="`${note.updated_at.toLocaleString('en-GB', {timeZone: 'UTC',timeStyle: 'short',dateStyle: 'short'})} UTC`"
               class="note__overview-date"/>
            <p v-text="note.title" class="note__overview-title"/>
            <pre v-text="note.content" class="note__overview-body" :class="{'is-loading': loading}"/>
        </div>
        <EditForm v-if="!loading" v-model:entry="note" :fields="fields" :class="{'note__body': note.title}"
                  @update:entry="updateNote"/>
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

        }
    },

    data() {
        return {
            tempNote: this.note,
            fields: Note.fields(),
            loading: false,
            startDragX: null,
            startDragY: null,
        }
    },

    methods: {
        updateNote() {
            this.loading = true;
            setTimeout(() => {
                this.loading = false;
            }, 500);
        },
        dragStart(event) {
            this.startDragX = event.screenX;
            this.startDragY = event.screenY;
        },
        dragEnd(event) {
            const xDifference = event.screenX - this.startDragX;
            const yDifference = event.screenY - this.startDragY;
            this.tempNote.x += xDifference;
            this.tempNote.y += yDifference;
            if (this.tempNote.x < 0) {
                this.tempNote.x = 0;
            }
            if (this.tempNote.y < 0) {
                this.tempNote.y = 0;
            }

            this.startDragX = null;
            this.startDragY = null;
        }
    },

    computed: {
        style() {
            return {
                left: `${this.tempNote.x}px`,
                top: `${this.tempNote.y}px`,
            }
        }
    }
}
</script>

<style lang="scss" scoped>
.note {
    position: absolute;
    width: 250px;

    &__sort-auto {
        position: static;
    }

    &__body {
        display: none;
    }

    &__overview {
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

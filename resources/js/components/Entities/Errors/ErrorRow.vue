<template>
    <tr class="table__row table__row--responsive">
        <td class="table__cell table__cell--narrow table__cell--action">
            <div class="split-buttons">
                <button class="button is-small is-info split-buttons__button split-buttons__button--first"
                        @click="$emit('view')"
                        :disabled="entry.status === 'deleting'">
                    <FontAwesomeIcon icon="eye" fixed-width/>
                </button>
                <div class="dropdown is-hidden-only-mobile">
                    <button class="button is-small split-buttons__button split-buttons__button--last">
                        <FontAwesomeIcon icon="caret-down" @click.stop="" fixed-width/>
                    </button>
                    <div class="dropdown__content">
                        <button class="button is-small is-danger dropdown__content-button" @click="$emit('delete')">
                            <FontAwesomeIcon icon="trash" class="button__icon" fixed-width/>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </td>
        <td class="table__cell" data-label="Created at" v-text="entry.created_at"/>
        <td class="table__cell table__cell--important" v-text="entry.url"/>
        <td class="table__cell table__cell--important" v-text="entry.message"/>
    </tr>
</template>

<script>
	export default {
		name: "ErrorRow",

        props: {
		    entry: {
		        type: Object,
                required: true
            }
        }
	}
</script>

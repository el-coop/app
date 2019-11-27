<template>
    <tr class="table__row  table__row--responsive">
        <td class="table__cell table__cell--centered table__cell--narrow table__cell--important"
            :class="{'is-loading': entity.status === 'uploading'}">
            <button class=" button is-small" @click.stop="$emit('edit')"
                    :class="{'is-success': entity.status === 'saved', 'is-danger': entity.status === 'error'}"
                    :disabled="entity.status === 'deleting'"
                    v-if="entity.status !== 'uploading'">
                <FontAwesomeIcon :icon="icon" fixed-width/>
            </button>
        </td>
        <td v-text="entity.name" class="table__cell table__cell--important"></td>
    </tr>
</template>

<script>
	export default {
		name: "EntityRow",
		props: {
			entity: {
				type: Object,
				required: true
			},
		},
		computed: {
			icon() {
				if (this.entity.status === 'error') {
					return 'exclamation';
				}

				return 'edit'
			},
		}
	}
</script>

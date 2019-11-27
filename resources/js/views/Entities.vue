<template>
    <div class="container" :class="{'is-loading': loading}">
        <div class="background-content">
            <EntityTable :entities="entities" @update="update">
            </EntityTable>
        </div>
    </div>
</template>

<script>
	import Entity from "../classes/Models/Entity";
	import EntityTable from "../components/Entities/EntityTable";
	import InteractsWithObjects from "../mixins/InteractsWithObjects";

	export default {
		name: "Entities",
		components: {EntityTable},
        mixins: [InteractsWithObjects],
		metaInfo: {
			title: 'Entities'
		},

		created() {
			this.load();
		},

		data() {
			return {
				entities: [],
				loading: false
			}
		},

		methods: {
			async load() {
				this.loading = true;
				if (!this.entities.length) {
					this.entities = await Entity.list();
				}
				this.loading = false;

			},
			update(entity) {
				this.updateById(this.entities, entity.id, entity);
			},
		}
	}
</script>

<style lang="scss" scoped>
    @import "~bulma/sass/utilities/initial-variables";
    @import "~bulma/sass/utilities/functions";
    @import "~bulma/sass/utilities/derived-variables";
    @import "~bulma/sass/utilities/mixins";
    @import "../../sass/variables";

    .container {
        display: flex;
        flex-direction: column-reverse;

        @include from($mobile) {
            display: block;
        }
    }
</style>

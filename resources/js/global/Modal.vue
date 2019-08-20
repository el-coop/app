<template>
    <div class="modal" :class="{'is-active': active}">
        <div class="modal-background" @click="$emit('change',false)"></div>
        <div class="modal-content">
            <div class="box">
                <slot></slot>
            </div>
        </div>
        <button class="modal-close is-large" aria-label="close" @click="$emit('change',false)"></button>
    </div>
</template>

<script>
	export default {
		name: "Modal",
		model: {
			prop: 'active',
			event: 'change'
		},

		props: {
			active: {
				type: Boolean,
				default: false
			}
		},

	}
</script>

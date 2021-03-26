<template>
    <div class="modal" :class="{'is-active': active}">
        <div class="modal-background" @click="$emit('update:active',false)"></div>
        <div class="modal-content" :class="{'modal-content--wide': wide,'modal-content--widest': widest}">
            <div class="card" :class="bodyClass">
                <slot></slot>
            </div>
        </div>
        <button class="modal-close is-large" @click="$emit('update:active',false)"/>
    </div>
</template>

<script>
export default {
    name: "Modal",

    props: {
        active: {
            type: Boolean,
            default: false
        },
        wide: {
            type: Boolean,
            default: false
        },
        widest: {
            type: Boolean,
            default: false
        },
        bodyClass: {
            type: String,
            default: ''
        }
    },

}
</script>

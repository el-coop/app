<template>
    <div class="field">
        <label class="label" v-if="options.label" :for="id" v-html="options.label"/>
        <ul>
            <li v-for="file in value" :key="file.id" class="table__row" :class="{ 'is-opaque' : ! file.checked}">
                <div class="table__cell">
                    <label class="switch">
                        <input type="checkbox" class="switch__input" v-model="file.checked">
                        <span class="switch__slider"/>
                    </label>
                    <a :href="`/transactions/attachment/${file.id}`" target="_blank" v-text="file.name"/>
                </div>
            </li>
        </ul>
        <p class="help is-info" v-if="options.help" v-text="options.help"/>
        <p class="help is-danger" v-if="error" v-text="error"/>
    </div>
</template>

<script>
    import FieldMixin from "./FieldMixin";

    export default {
        name: "FileArrayField",
        mixins: [FieldMixin],

        props: {
            value: {
                type: Array,
                default() {
                    return [];
                }
            },
            error: {
                type: String,
                default: ''
            },
            limit: {
                type: Number,
                default: 5
            }

        },
    }
</script>

<style scoped lang="scss">
    .table__cell {
        display: flex;
        align-items: center;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: calc(var(--size-6) * 2);
        height: var(--size-6);
        margin-right: var(--size-6);

        &__input {
            display: none;

            &:checked + .switch__slider {
                background-color: var(--toast-success);

                &:before {
                    transform: translateX(calc(var(--size-6) * 0.35));
                }
            }
        }

        &__slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--toast-danger);
            transition: var(--speed);
            border-radius: var(--radius-rounded);

            &:before {
                position: absolute;
                content: "";
                height: calc(var(--size-6) * 0.9);
                width: calc(var(--size-6) * 1.5);
                left: calc(var(--size-6) * 0.05);
                bottom: calc(var(--size-6) * 0.05);
                background-color: var(--card-background);
                transition: var(--speed);
                border-radius: var(--radius-rounded);
            }
        }

    }


</style>

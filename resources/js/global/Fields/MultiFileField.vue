<template>
    <div class="field">
        <label class="dropzone" :class="{'dropzone--empty': ! Object.keys(value).length, 'is-loading': loading}">
            <input class="dropzone__input" type="file" @change="fileAdded" :multiple="limit > 1"
                   accept="image/*">
            <template v-if="Object.keys(value).length">
                <div v-for="(file,key) in value" :key="key" class="dropzone__preview" @click.prevent="removeFile(key)">
					<span class="dropzone__icon">
						<FontAwesomeIcon icon="file"/>
                    </span>
                    <span v-text="file.name"/>
                    <div class="dropzone__preview-label is-danger">
                        <FontAwesomeIcon icon="times-circle"/>
                        (click to remove)
                    </div>
                </div>
                <div class="dropzone__preview" v-if="Object.keys(value).length < this.limit">
					<span class="dropzone__icon">
						<FontAwesomeIcon icon="upload"/>
					</span>
                    <span>
						Add files<br>
						<span v-text="`${this.limit - Object.keys(value).length} Left`"/>
					</span>
                </div>
            </template>
            <template v-else>
				<span class="dropzone__icon">
					<FontAwesomeIcon icon="upload"/>
				</span>
                <span>
					Add files<br>
					<span v-text="`Maximum ${this.limit}`"/>
				</span>
            </template>
        </label>
        <p class="help is-danger" v-if="error" v-text="error"/>
    </div>
</template>

<script>
    export default {
        name: "MultiFileField",

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
            },
            options: {
                required: false
            }

        },

        data() {
            return {
                loading: false
            }
        },

        methods: {
            async fileAdded(event) {
                const filesObject = this.value;
                const files = event.target.files;
                this.loading = true;
                for (let i = 0; i < files.length && Object.values(filesObject).length < this.limit; i++) {
                    const file = files[i];
                    filesObject.push(file);
                }
                this.$emit('input', filesObject);
                this.loading = false;
            },

            removeFile(key) {
                const filesObject = this.value;
                delete filesObject[key];
                this.$emit('input', filesObject);
            }
        },
    }
</script>

<style lang="scss" scoped>
    .dropzone {
        cursor: pointer;
        border: 1px dashed var(--border-color);
        border-radius: 5px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        min-height: 120px;
        position: relative;
        color: var(--text);

        &--empty {
            flex-direction: column;
            text-align: center;
        }

        &__input {
            height: 100%;
            left: 0;
            opacity: 0;
            outline: none;
            position: absolute;
            top: 0;
            width: 100%;
            cursor: pointer;
        }

        &__icon {
            height: 1.5em;
            width: 1.5em;
            text-align: center;
        }

        &__preview {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;

            width: 120px;
            height: 120px;
            margin: 8px;

            border-color: var(--border-color);
            border-style: solid;
            border-width: 1px;
            border-radius: 4px;

            z-index: 10;

            &-label {
                font-size: var(--size-7);
                width: 100%;
                padding: 0 2.5%;

                &-text:only-child {
                    margin: auto;
                }

                &-icon {
                    margin-right: 0.5em;
                }
            }

            &-image {
                height: 85%;
            }

        }
    }

</style>

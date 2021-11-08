<template>
    <div class="field">
        <label class="dropzone" :class="{'dropzone--empty': ! Object.keys(modelValue).length, 'is-loading': loading}">
            <input class="dropzone__input" type="file" @change="fileAdded" :multiple="limit > 1"
                   :accept="accept">
            <template v-if="Object.keys(modelValue).length">
                <div v-for="(file,key) in modelValue" :key="key" class="dropzone__preview" @click.prevent="removeFile(key)">
					<span class="">
						<FontAwesomeIcon icon="file"/>
                    </span>
                    <span v-text="file.name" class="dropzone__preview-text"/>
                    <div class="dropzone__preview-label is-danger">
                        <FontAwesomeIcon icon="times-circle"/>
                        (click to remove)
                    </div>
                </div>
                <div class="dropzone__preview" v-if="Object.keys(modelValue).length < this.limit">
					<span class="dropzone__icon">
						<FontAwesomeIcon icon="upload"/>
					</span>
                    <span>
						Add files<br>
						<span v-text="`${this.limit - Object.keys(modelValue).length} Left`"/>
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
        <p class="help is-info" v-if="options.help" v-text="options.help"/>
        <p class="help is-danger" v-if="error" v-text="error"/>
    </div>
</template>

<script>
    export default {
        name: "MultiFileField",

        props: {
            modelValue: {
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
            },
            accept: {
                default: '',
                type:String
            }

        },

        data() {
            return {
                loading: false
            }
        },

        methods: {
            async fileAdded(event) {
                const filesObject = this.modelValue;
                const files = event.target.files;
                this.loading = true;
                for (let i = 0; i < files.length && Object.values(filesObject).length < this.limit; i++) {
                    const file = files[i];
                    filesObject.push(file);
                }
                this.$emit('update:modelValue', filesObject);
                this.loading = false;
            },

            removeFile(key) {
                const filesObject = this.modelValue;
                filesObject.splice(key,1);
                this.$emit('update:modelValue', filesObject);
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

            &-text {
                word-break: break-word;
            }

            &-label {
                font-size: var(--size-7);
                width: 100%;
                padding: 0 2.5%;
            }

            &-image {
                height: 85%;
            }

        }
    }

</style>

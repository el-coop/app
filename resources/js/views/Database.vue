<template>
    <div class="container">
        <div class="card" :class="{'is-loading' : loading}">
            <div class="card__header"><h5 class="title is-size-6">Schedule emailed backups</h5></div>
            <CheckboxField v-model="backup" :options="{
                label: 'Receive email backups'
            }"/>
            <template v-if="backup">
                <SelectField :options="{
                    label: 'Select frequency',
                    options: {
                        '0 0 * * 0': 'Once a week',
                        '0 0 1,15 * *': 'Twice a month',
                        '0 0 * * *': 'Every day',
                    }
                }" v-model="frequency"/>

                <TextField :options="{
                    label: 'Or use custom cron frequency (<a href=\'https://crontab.guru/\' target=\'_blank\'>Cron Help</a>)',
                }" :error="errors['frequency'] ? errors['frequency'][0] : ''" v-model="frequency"/>
            </template>

            <button class="button is-success is-fullwidth" @click="submit">Update settings</button>
        </div>

        <div class="card">
            <div class="card__header"><h5 class="title is-size-6">Database Backup</h5></div>
            <a href="/database/backup" target="_blank" class="is-size-3">Download backup</a>
        </div>
    </div>
</template>

<script>
    import CheckboxField from "../global/Fields/CheckboxField.vue";
    import SelectField from "../global/Fields/SelectField.vue";
    import TextField from "../global/Fields/TextField.vue";
    import FormException from '../classes/FormException';

    export default {
        name: "Database",
        components: {SelectField, CheckboxField, TextField},
        metaInfo: {
            title: 'Database'
        },

        created() {
            this.loadActions();
        },

        data() {
            return {
                backup: null,
                frequency: '',
                loading: true,
                errors: []
            }
        },

        methods: {
            async loadActions() {
                const actions = (await this.$store.dispatch('User/scheduledActions'));
                const backup = actions.backupDatabase ?? false;
                this.loading = false;
                this.backup = !!backup;
                this.frequency = backup ? backup : '';
            },

            async submit() {
                if (this.backup && !this.frequency) {
                    this.errors.frequency = ['The frequency is required'];
                    return;
                }
                this.loading = true;
                this.errors = [];
                try {
                    await this.$store.dispatch('User/saveAction', {
                        action: 'backupDatabase',
                        frequency: this.backup ? this.frequency : null
                    });
                    this.$toast.success(' ', 'Settings updated');

                } catch (exception) {
                    if (exception instanceof FormException) {
                        this.errors = exception.validationErrors;
                        this.$toast.error('Please try again', 'Settings update validation error');
                    } else {
                        console.log(exception);
                        this.$toast.error('Please try again', 'Settings update error');
                    }
                }
                this.loading = false;
            }
        }

    }
</script>

<style lang="scss" scoped>
    @import "node_modules/bulma/sass/utilities/initial-variables";
    @import "node_modules/bulma/sass/utilities/functions";
    @import "node_modules/bulma/sass/utilities/derived-variables";
    @import "node_modules/bulma/sass/utilities/mixins";
    @import "../../sass/variables";

    .container {
        padding: 10px;

        @include from($desktop) {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: auto;
            gap: var(--gap);
            padding: 10px 30px;
        }
    }
</style>

<template>
    <div class="container" :class="{'is-loading': loading}">
        <div class="background-content">
        </div>
    </div>
</template>

<script>

export default {
    name: "Notes",
    metaInfo: {},

}
</script>

<style lang="scss" scoped>
@import "~bulma/sass/utilities/initial-variables";
@import "~bulma/sass/utilities/functions";
@import "~bulma/sass/utilities/derived-variables";
@import "~bulma/sass/utilities/mixins";
@import "../../sass/variables";

</style>

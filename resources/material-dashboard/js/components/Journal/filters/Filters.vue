<template>
    <div class="journal__sort">
        <div class="journal__sort__content">
            <incr-decr :incrDecr="incrDecr"></incr-decr>
        </div>
        <div class="journal__sort__before"></div>
    </div>
</template>

<script>
import IncrDecr from "./IncrDecr";

export default {
    props: ['incrDecr'],
    name: "Main",
    components: {
        IncrDecr
    }
}
</script>

<style scoped>

</style>

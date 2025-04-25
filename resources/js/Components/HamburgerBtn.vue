<template>
    <btn :disabled="disabled"  :tooltip="this.$props.tooltip" class="btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 50 50">
            <path d="M 3 9 A 1.0001 1.0001 0 1 0 3 11 L 47 11 A 1.0001 1.0001 0 1 0 47 9 L 3 9 z M 3 24 A 1.0001 1.0001 0 1 0 3 26 L 47 26 A 1.0001 1.0001 0 1 0 47 24 L 3 24 z M 3 39 A 1.0001 1.0001 0 1 0 3 41 L 47 41 A 1.0001 1.0001 0 1 0 47 39 L 3 39 z"></path>
        </svg>
    </btn>
</template>

<script>
import Btn from "@/Components/Btn.vue";
export default {
    name: "HamburgerBtn",
    components: {Btn},
    props:{
        disabled: false,
        tooltip: String,
    }
}
</script>

<style scoped>
.btn{
    padding: 0;
}
svg{
    padding: 5px;
    fill: white;
}
svg:hover{
    fill:var(--color-first)
}
</style>

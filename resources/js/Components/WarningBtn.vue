<template>
    <btn :disabled="disabled"  class="btn">
        <slot></slot>
    </btn>
</template>

<script>
import Btn from "@/Components/Btn.vue";
export default {
    name: "WarningBtn",
    components: {Btn},
    props:{
        disabled: false,
    }
}
</script>

<style scoped>
.btn{
    color: white;
    font-weight: bold;
}
.btn:hover{
    background-color: #d40000;
}
</style>

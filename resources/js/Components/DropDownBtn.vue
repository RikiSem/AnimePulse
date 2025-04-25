<template>
    <warning-btn v-if="this.warningBtn" class="dd-btn">
        <slot></slot>
    </warning-btn>
    <btn :disabled="disabled"  :tooltip="this.$props.tooltip" v-else class="dd-btn">
        <slot></slot>
    </btn>
</template>

<script>
import Btn from "@/Components/Btn.vue";
import WarningBtn from "@/Components/WarningBtn.vue";
export default {
    name: "DropDownBtn",
    components: {WarningBtn, Btn},
    props:{
        disabled: false,
        tooltip: String,
        warningBtn: {
            type: Boolean,
            default: false
        }
    }
}
</script>

<style scoped>
.dd-btn{
    border-radius: 0;
    border: none;
    width: 100%;
}
</style>

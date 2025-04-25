<template>
    <button :disabled="disabled" v-if="tooltip === ''" class="default-border-color-sec transition">
        <slot></slot>
    </button>
    <button :disabled="disabled"  v-else :data-tooltip="tooltip" class="default-border-color-sec transition">
        <slot></slot>
    </button>
</template>

<script>
export default {
    name: "Btn",
    props:{
        disabled: {
            type: Boolean,
            default: false,
        },
        tooltip: {
            type: String,
            default: ''
        }
    }
}
</script>

<style scoped>
button{
    border-radius: 0.7rem;
    padding: 10px;
}
button:hover{
    background-color: var(--color-sec);
}
[data-tooltip]:hover::after {
    background-color: #878787;
    color: white;
    border-radius: 5px;
    padding: 5px;
    position: absolute;
    transform: translate(-30px, 20px);
    content: attr(data-tooltip);
}
</style>

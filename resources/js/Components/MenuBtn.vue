<template>
<button>
    <p>
        <slot></slot>
    </p>
</button>
</template>

<script>
export default {
    name: "MenuBtn"
}
</script>

<style scoped>
button{
    height: 100%;
    border-radius: 0;
    padding-left: 10px;
    padding-right: 10px;
    color: var(--default-font-color);
}
button:hover{
    background-color: var(--color-sec);
    border-radius: 0;
}
p{
    text-align: center;
    margin: auto;
    font-weight: bold;
    font-size: clamp(1rem, 0.25rem + 2vw, 0.25rem);
}
</style>

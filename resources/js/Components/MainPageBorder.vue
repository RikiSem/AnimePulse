<template>
    <div class="border-custom">
        <h5 class="border-name">
            <slot name="border-name"></slot>
        </h5>
        <slot name="border-content"></slot>
    </div>
</template>

<script>
export default {
    name: "MainPageBorder"
}
</script>

<style scoped>
.border-custom{
    border: 2px solid var(--color-sec);
    border-radius: 1rem;
    padding: 20px 0px 20px 0px;
    margin-top: 50px;
    margin-bottom: 50px;
    width: 100%;
    height: 100%;
}
.border-name{
    width: fit-content;
    font-size: 1.25rem;
    background-color: var(--color-first);
    position: absolute;
    padding: 10px;
    font-weight: bold;
    transform: translate(1.5rem, -3rem);
}
@media only screen and (max-width: 850px) {
    .border-name{
        font-size: 0.8rem;
        transform: translate(1.5rem, -2.5rem);
    }
}
</style>

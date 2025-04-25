<template>
    <div class="content">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "AnimatedHeaderBase"
}
</script>

<style scoped>
.content {
    margin: 0 auto;
    width: 100%;
    height: 100%;
    position: relative;
    overflow: hidden;
    border-top: 3px solid var(--color-sec);
    border-bottom: 3px solid var(--color-sec);
    z-index: -5;
}
</style>

<template>
    <img class="avatar" :src="this.$props.src" alt="Упс:(">
</template>

<script>
export default {
    name: "Avatar",
    props:{
        src: String,
    }
}
</script>

<style scoped>
img{
    width: 20rem;
}
</style>

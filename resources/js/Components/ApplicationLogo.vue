<template>
    <a href="/"><img :src="imageUrl"></a>
</template>
<script setup>
import imageUrl from '@/../imgs/logo.png'
</script>
<style scoped>
img{
    width: clamp(9rem, 1rem + 2rem, 1rem);
    min-width: 3.3rem;
    border-radius: 20%;
    margin: 10px;
}
</style>

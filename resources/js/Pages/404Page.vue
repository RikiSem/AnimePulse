<template>
    <Head title="Как ты сюда попал?"></Head>
    <page-template :pages="this.$props.pages">
        <template #content>
            <img :src="img" alt="Упс:(">
        </template>
    </page-template>
</template>

<script>
import PageTemplate from "@/Layouts/PageTemplate.vue";
import { Head } from '@inertiajs/vue3'
export default {
    name: "404Page",
    components: {PageTemplate, Head},
    props: {
        img: String,
        pages: Array,
    }
}
</script>

<style scoped>

</style>

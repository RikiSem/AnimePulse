<template>
    <main-layout :pages="this.$props.pages" :user="this.$props.user">
        <template v-slot:auth-menu>
            <auth-menu :user="this.$props.user"></auth-menu>
        </template>
        <template v-slot:body>
            <slot name="content"></slot>
        </template>
    </main-layout>
</template>

<script>
import MainLayout from "@/Layouts/MainLayout.vue";
import AuthMenu from "@/Components/AuthMenu.vue";
export default {
    name: "PageTemplate",
    components: {AuthMenu, MainLayout},
    props:{
        pages: Array,
        user: Object,
    }
}
</script>

<style scoped>

</style>

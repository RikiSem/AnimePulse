<template>
    <Head :title="String().concat('Комментарии пользователя', this.$props.user.name)"></Head>
    <page-template :pages="pages" :user="this.$attrs.auth.user">
        <template #content>
            <user-page-menu :user="this.$props.user"></user-page-menu>
            <p class="h2">
                Комментарии пользователя {{ this.$props.user.name }}
            </p>
            <div class="content">
                <div class="comment-list">
                    <div class="py-3 bg-color-first">
                        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                            <div class="shadow-sm">
                                <comments-list :items="this.$props.comments" :current-user="this.$props.user"></comments-list>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </page-template>
</template>

<script>
import PageTemplate from "@/Layouts/PageTemplate.vue";
import Btn from "@/Components/Btn.vue";
import Avatar from "@/Components/Avatar.vue";
import CommentsList from "@/Components/CommentsList.vue";
import UserPageMenu from "@/Components/UserPageMenu.vue";
import { Head } from '@inertiajs/vue3';
export default {
    name: "UserPageComments",
    components: {UserPageMenu, CommentsList, Avatar, Btn, PageTemplate, Head},
    props:{
        pages: Array,
        user: Object,
        comments: Array,
    }
}
</script>

<style scoped>
.content{
}
.user-info{
    padding: 20px 30px 40px 30px;
    background-color: var(--color-third);
    box-shadow: 0 0 40px 30px var(--color-first) inset;
    border-radius: 2rem;
    display: flex;
    flex-direction: column;
}
.user-info div{
    display: flex;
    flex-direction: column;
    margin: auto;
    text-align: center;
}
td, th{
    padding: 5px;
}
</style>

<template>
    <Head title="Главная"></Head>
    <page-template :pages="pages" :user="$attrs.auth.user">
        <template v-slot:content>
            <main-page-border>
                <template v-slot:border-name>
                    <template v-if="currentSeason === '1'">
                        Аниме текущего сезона
                    </template>
                    <template v-else>
                        Аниме сезона <span class="season-name">"{{ currentSeason }}"</span>
                    </template>
                </template>
                <template v-slot:border-content>
                    <season-anime-list :items="this.seasonAnimeList"></season-anime-list>
                </template>
            </main-page-border>
            <div class="mid-main-page-content row">
                <main-page-border class="col">
                    <template v-slot:border-name>
                        Новые рецензии
                    </template>
                    <template v-slot:border-content>
                        <list :items="newReviews"></list>
                    </template>
                </main-page-border>
                <div style="width: 20px"></div>
                <main-page-border class="col">
                    <template v-slot:border-name>
                        Популярные рецензии
                    </template>
                    <template v-slot:border-content>
                        <list :items="popularReviews"></list>
                    </template>
                </main-page-border>
            </div>
            <!--<main-page-border>
                <template v-slot:border-name>
                    Видео обзоры
                </template>
                <template v-slot:border-content>
                    <video-list :items="newVideos"></video-list>
                </template>
            </main-page-border>-->
        </template>
    </page-template>
</template>

<script>
import MainLayout from "@/Layouts/MainLayout.vue";
import SeasonAnimeList from "@/Components/SeasonAnimeList.vue";
import MainPageBorder from "@/Components/MainPageBorder.vue";
import List from "@/Components/ReviewList.vue";
import VideoList from "@/Components/VideoList.vue";
import AuthMenu from "@/Components/AuthMenu.vue";
import PageTemplate from "@/Layouts/PageTemplate.vue";
import { Head } from '@inertiajs/vue3';
export default {
    name: "MainPage",
    components: {PageTemplate, AuthMenu, VideoList, List, MainPageBorder, SeasonAnimeList, MainLayout, Head},
    props: {
        pages: Object,
        seasonAnimeList: Object,
        newReviews: Object,
        popularReviews: Object,
        newVideos: Object,
        currentSeason: String,
        currentMonth: Number,
    }
}
</script>

<style scoped>
.mid-main-page-content{
    display: flex;
    flex-wrap: wrap
}
.season-name{
    text-transform: uppercase;
}
@media only screen and (max-width: 500px){
    .mid-main-page-content{
        flex-direction: column;
    }
}
</style>

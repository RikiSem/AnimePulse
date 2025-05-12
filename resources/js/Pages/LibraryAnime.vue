<template>
    <Head>
        <title>Библиотека</title>
        <meta name="description" content="Исследуйте нашу коллекцию аниме всех жанров и эпох. В нашей библиотеке представлены как классические тайтлы, так и новинки сезона. Найдите своё следующее любимое аниме с удобным поиском и фильтрами.">
    </Head>
    <page-template :pages="pages" :user="$attrs.auth.user">
        <template v-slot:content>
            <anime-list-filter
                @filterData="filterData => this.applyFilter(filterData)"
                :user-id="this.$props.userId"
                :statuses-for-user="statusForUser"
                :anime-statuses="animeStatuses"
                :anime-types="animeTypes"
                :anime-release-years="animeReleaseYears"
                :studios="studios"
                :filter-route="'library.anime.all'"
                class="mx-5 my-5 default-border-color-sec p-6">

            </anime-list-filter>
            <div v-if="this.result.length > 0">
                <div class="animes">
                    <anime-card v-for="anime in this.result" :anime="anime"></anime-card>
                </div>
                <btn @click="setOffsetAndGet" style="width:100%;">Еще аниме</btn>
            </div>
            <div v-else style="text-align: center">
                Ничего не найдено
            </div>
        </template>
    </page-template>
</template>

<script>
import MainLayout from "@/Layouts/MainLayout.vue";
import AuthMenu from "@/Components/AuthMenu.vue";
import AnimeListFilter from "@/Components/AnimeListFilter.vue";
import AnimeCard from "@/Components/AnimeCard.vue";
import Btn from "@/Components/Btn.vue";
import UpScroll from "@/Components/UpScroll.vue";
import PageTemplate from "@/Layouts/PageTemplate.vue";
import { Head } from '@inertiajs/vue3';
export default {
    name: "LibraryAnime",
    components: {PageTemplate, UpScroll, Btn, AnimeCard, AnimeListFilter, AuthMenu, MainLayout, Head},
    data(){
        return {
            result: [],
            offset: 0,
            params: [],
        }
    },
    props:{
        pages: Array,
        animeReleaseYears: Array,
        studios: Array,
        animeStatuses: Array,
        statusForUser: Array,
        animeTypes: Array,
        userId: Number,
        params: {
            type: Array,
            default: () => []
        }
    },
    mounted() {
        this.params = this.$props.params
        this.getAnime();
    },
    methods:{
        applyFilter(filterData){
            filterData.offset = this.offset
            this.params = filterData
            if (this.$props.params.tags !== undefined) {
                this.params.tags.push(this.$props.params.tags)
            }
            if (this.$props.params.studio !== undefined) {
                this.params.studio = this.$props.params.studio
            }
            this.result = []
            this.getAnime()
        },
        setOffsetAndGet()
        {
            this.offset ++
            this.getAnime()
        },
        async getAnime() {
            let response = await (axios.post(route('library.anime.all'), {
                'offset': this.offset,
                'params': this.params,
            }));
            for (let anime of response.data) {
                this.result.push(anime)
            }
        },
    }
}
</script>

<style scoped>
.animes{
    height: 100%;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}
</style>

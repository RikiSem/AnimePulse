<template>
    <Head>
        <title>Библиотека</title>
        <meta name="description" content="Исследуйте нашу коллекцию аниме всех жанров и эпох. В нашей библиотеке представлены как классические тайтлы, так и новинки сезона. Найдите своё следующее любимое аниме с удобным поиском и фильтрами.">
    </Head>
    <page-template :pages="pages" :user="$attrs.auth.user">
        <template v-slot:content>
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

import PageTemplate from "@/Layouts/PageTemplate.vue";
import {Head} from "@inertiajs/vue3";
import Btn from "@/Components/Btn.vue";
import Dropdown from "@/Components/Dropdown.vue";
import EntityPageLayout from "@/Layouts/EntityPageLayout.vue";
import WriteReview from "@/Components/WriteReview.vue";
import Modal from "@/Components/Modal.vue";
import AnimeCard from "@/Components/AnimeCard.vue";

export default {
    components: {
        AnimeCard,
        PageTemplate,
        Dropdown,
        Btn,
        EntityPageLayout,
        WriteReview, Modal, Head},
    name: 'AnimeByStudioPage',
    data(){
        return {
            result: [],
            offset: 1,
            params: [],
        }
    },
    props:{
        pages: Array,
        animes: Array,
    },
    methods:{
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

</style>

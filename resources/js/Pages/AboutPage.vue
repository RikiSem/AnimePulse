<template>
    <Head title="О сайте"></Head>
    <page-template :pages="pages" :user="this.$attrs.auth.user">
        <template #content>
            <div class="content">
                <div class="about-img">
                    <img :src="this.$props.img" alt="Упс:(" style="width: 70rem; box-shadow: var(--color-first) 0 0 60px 40px">
                </div>
                <div class="about-text">
                    <main-page-border>
                        <template #border-name>
                            О сайте
                        </template>
                        <template #border-content>
                            <div style="margin: 0 20px 0 20px">
                                <template v-for="(text, index) in this.$props.text">
                                    <p class="text" v-if="index === 0" style="text-indent: 3rem">
                                        {{text}}
                                    </p>
                                    <p class="text" v-else>
                                        {{text}}
                                    </p>
                                </template>
                            </div>
                        </template>
                    </main-page-border>
                </div>
                <div class="contacts">
                    <main-page-border>
                        <template #border-name>
                            Контакты
                        </template>
                        <template #border-content>
                            <div class="contacts-div" style="margin: 0 20px 0 20px">
                                <div class="social-item tg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-telegram" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.287 5.906q-1.168.486-4.666 2.01-.567.225-.595.442c-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294q.39.01.868-.32 3.269-2.206 3.374-2.23c.05-.012.12-.026.166.016s.042.12.037.141c-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8 8 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629q.14.092.27.187c.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.4 1.4 0 0 0-.013-.315.34.34 0 0 0-.114-.217.53.53 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09"/>
                                    </svg>
                                    <a :href="this.$props.social.tg.link" target="_blank">
                                        Наш канал
                                    </a>
                                </div>
                            </div>
                        </template>
                    </main-page-border>
                </div>
            </div>
        </template>
    </page-template>
</template>

<script>
import PageTemplate from "@/Layouts/PageTemplate.vue";
import MainPageBorder from "@/Components/MainPageBorder.vue";
import { Head } from '@inertiajs/vue3'
export default {
    name: "AboutPage",
    components: {MainPageBorder, PageTemplate, Head},
    date(){
        return {
            host: location.hostname,
        }
    },
    props:{
        social: Array,
        img: String,
        text: Array,
        pages: Array,
    }
}
</script>

<style scoped>
.social-item{
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
}
.social-item > *{
    height: fit-content;
    margin: auto 5px auto 5px;
}
.content{
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.content, .about-img, .contacts, .about-text{
    margin: 10px auto 10px auto;
    width: fit-content;
}
.about-text .text{
    font-size: 1.5rem;
}
@media only screen and (max-width: 1000px){
    .about-text .text{
        font-size: 0.9rem;
    }
}
</style>

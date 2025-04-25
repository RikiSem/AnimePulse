<template>
    <Head title="Админка"></Head>
    <page-template :pages="pages" :user="$attrs.auth.user">
        <template v-slot:content>
            <div class="main">
                <div class="left-side">
                    <div class="admin-menu">
                        <nav-link class="default-border" :href="route('admin')">
                            <menu-btn style="margin: auto">Рецензии</menu-btn>
                        </nav-link>
                        <nav-link class="default-border" :href="route('admin')">
                            <menu-btn style="margin: auto">Багрепорты</menu-btn>
                        </nav-link>
                    </div>
                </div>
                <div class="right-side mx-5">
                    <p class="h1">Рецензии на модерации</p>
                    <template v-for="review in this.$props.data.original">
                        <div class="moderation-item default-box-shadow">
                            <a :href="route('review.show', {
                                'id': review.id
                            })">
                                <div class="moderation-info">
                                    <p class="h4">Аниме: {{ review.anime.name }}</p>
                                    <p class="h4">Автор: {{ review.author.name }}</p>
                                </div>
                            </a>
                            <div class="btns">
                                <btn @click="moderationAccess(review.id)">Одобрить</btn>
                                <warning-btn @click="moderationReject(review)">Отклонить</warning-btn>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </page-template>
    <modal @close="this.showModal = false" :show="this.showModal">
        <div>
            <p class="h3">Причина не прохождения модерации модерации</p>
            <p><input placeholder="Причина отказа" :value="this.rejectModerationReason" @input="event => this.rejectModerationReason = event.target.value"></p>
            <p><btn @click="reject">Отправить</btn></p>
        </div>
    </modal>
</template>

<script>
import PageTemplate from "@/Layouts/PageTemplate.vue";
import MenuBtn from "@/Components/MenuBtn.vue";
import NavLink from "@/Components/NavLink.vue";
import Review from "@/Components/Review.vue";
import Btn from "@/Components/Btn.vue";
import WarningBtn from "@/Components/WarningBtn.vue";
import Modal from "@/Components/Modal.vue";
import { Head } from '@inertiajs/vue3'
export default {
    name: "AdminPage",
    components: {Modal, WarningBtn, Btn, Review, NavLink, MenuBtn, PageTemplate, Head},
    data(){
        return {
            showModal: false,
            selectededReview: null,
            rejectModerationReason: '',
        }
    },
    props:{
        pages: Array,
        data: null
    },
    methods:{
        async moderationAccess(reviewId)
        {
            await (axios.post(route('review.accept'), {
                review_id: reviewId
            }))
        },
        moderationReject(data){
            this.showModal = true;
            this.selectededReview = data;
        },
        async reject(){
            await (axios.post(route('review.reject'), {
                review_id: this.selectededReview.id,
                reason: this.rejectModerationReason
            }))
            this.$emit('close');
        }
    }
}
</script>

<style scoped>
.main{
    display: grid;
    grid-template-columns: 20% 80%;
}
.admin-menu{
    width: 100%;
    display: flex;
    flex-direction: column;
}
.moderation-item{
    padding: 10px;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}
.btns{
    display: flex;
    flex-direction: column;
    justify-content: space-evenly;
}
.moderation-info{
    display: flex;
    flex-direction: column;
    justify-content: space-evenly;
}
</style>

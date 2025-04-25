<template>
    <Head :title="String().concat('Рецензия на аниме ', review.anime.name)"></Head>
    <entity-page-layout
        @moreComments="setOffsetAndGet(0)"
        @moreReviews="setOffsetAndGet(1)"
        @sendComment="data => sendComment(data)"
        :pages="pages"
        :user="this.$attrs.auth.user"
        :comments="this.comments"
    >
        <template #content>
            <div class="name-actions-stat">
                <div class="name">
                    <p class="h1">Рецензия к аниме <a class="anime-name" :href="route('anime.show', {
                        id: review.anime.id
                    })">{{ review.anime.name }}</a>
                        <span v-if="isAuthor"> ( Статус модерации: {{ review.status.readable }})</span>
                        <span v-if="isAuthor && review.reject_reason !== null"> Причина отклонения: {{ review.reject_reason }}</span>
                    </p>
                </div>
                <div v-if="this.isLogin === true && this.isAuthor === false" class="stats">
                    <reaction-btn @btnClick="checkReaction(1)">
                        {{ likes }}
                    </reaction-btn>
                    <reaction-btn @btnClick="checkReaction(0)" :dislike="true">
                        {{ dislikes }}
                    </reaction-btn>
                    <!--<div class="views">
                        0
                    </div>-->
                </div>
                <div v-if="this.isLogin === true" class="actions">
                    <dropdown v-if="this.isAuthor">
                        <template #trigger>
                            <hamburger-btn tooltip="Управление рецензией" class="ham-btn"></hamburger-btn>
                        </template>
                        <template #content>
                            <template v-if="this.isAuthor" class="author-review-control">
                                <drop-down-btn v-if="this.canEdit" @click="this.showChangeModal = true">Редактировать</drop-down-btn>
                                <drop-down-btn :disabled="true" v-else tooltip="Редактировать можно в первый час после публикации и если рецензия не на модерации" @click="this.showChangeModal = true">Редактировать</drop-down-btn>
                                <drop-down-btn @click="this.showDeleteModal = true" :warning-btn="true">Удалить</drop-down-btn>
                            </template>
                            <!--<template v-else class="review-control">
                                Тут что то будет
                            </template>-->
                        </template>
                    </dropdown>
                </div>
            </div>
            <div class="review">
                <div class="review-content">
                    <div class="review-text" v-html="review.html"></div>
                    <div class="review-marks default-border-top-color-sec">
                        <main-page-border>
                            <template #border-name>
                                <p class="h3">Оценки</p>
                            </template>
                            <template #border-content>
                                <div class="midterm-mark">
                                    <p class="h3">Оценки по критериям</p>
                                    <div class="midterm-marks">
                                        <div class="mark" v-for="midterm_mark in review.midterm_mark">
                                            <p class="mark-name">
                                                {{ midterm_mark.name }}
                                            </p>
                                            <p class="mark-mark">
                                                {{ midterm_mark.mark }}/10
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="final-mark">
                                    <p class="h3">Итоговая оценка</p>
                                    <div class="final-mark-mark mark">
                                        <p class="h1">
                                            {{ review.final_mark }}/10
                                        </p>
                                    </div>
                                </div>
                            </template>
                        </main-page-border>
                    </div>
                </div>
                <div class="author-info">
                    <p class="review-author">
                        Автор: <a :href="route('user', {
                            id: review.author.id
                        })">{{ review.author.name }}</a>
                    </p>
                    <p class="review-date">
                        {{ new Date(review.updated_at).toLocaleString() }}
                    </p>
                </div>
            </div>
        </template>
    </entity-page-layout>
    <modal :show="this.showChangeModal" @close="this.showChangeModal = false" class="change-review-modal">
        <edit-review @close="this.showChangeModal = false" :marks="review.midterm_mark" :text="review.html" :id="review.id"></edit-review>
    </modal>
    <modal :show="this.showDeleteModal" @close="this.showDeleteModal = false" class="delete-review-modal">
        <div class="delete-review-modal-content">
            <p class="h1">Вы действительно хотите удалить рецензию?</p>
            <div class="btns">
                <btn @click="this.showDeleteModal = false">Нет</btn>
                <warning-btn @click="deleteReview" style="color: black;">Да</warning-btn>
            </div>
        </div>
    </modal>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import EntityPageLayout from "@/Layouts/EntityPageLayout.vue";
import MainPageBorder from "@/Components/MainPageBorder.vue";
import Btn from "@/Components/Btn.vue";
import ReactionBtn from "@/Components/ReactionBtn.vue";
import HamburgerBtn from "@/Components/HamburgerBtn.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropDownBtn from "@/Components/DropDownBtn.vue";
import Modal from "@/Components/Modal.vue";
import WarningBtn from "@/Components/WarningBtn.vue";
import EditReview from "@/Components/EditReview.vue";
export default {
    name: "ReviewPage",
    components: {
        EditReview,
        WarningBtn,
        Modal, DropDownBtn, Dropdown, HamburgerBtn, ReactionBtn, Btn, MainPageBorder, EntityPageLayout, Head},
    data(){
        return {
            isAuthor: false,
            isLogin: false,
            canEdit: false,
            comments: [],
            commentOffset: 0,
            likes: this.$props.review.like_count,
            dislikes: this.$props.review.dislike_count,
            currentUserSetReaction: {
                type: -1,
                isSet: false
            },
            showChangeModal: false,
            showDeleteModal: false,
        }
    },
    props:{
        id: Number,
        pages: Array,
        review: Object,
    },
    mounted() {
        this.isLogin = this.$attrs.auth.user !== null;
        this.isAuthor = this.isLogin && this.$attrs.auth.user.id === this.$props.review.author.id;
        this.canEdit = this.isAuthor && (new Date() - new Date(this.$props.review.updated_at)) / (1000 * 60 * 60) < 1 && this.review.status.system === 'moderation_fail';
        this.getComments(0);
    },
    methods:{
        async deleteReview(){
            await (axios.post(route('review.delete'), {
                id: this.$props.id
            }))
            window.location.href = route('anime.show', {
                id: this.review.anime.id
            })
        },
        checkReaction(type){
            if (this.currentUserSetReaction.type !== type) {
                if (this.review.reactions.length > 0) {
                    for (let reaction of this.review.reactions) {
                        if (reaction.user_id === this.$attrs.auth.user.id && reaction.type === type) {
                            this.decreaseReaction(type)
                            this.sendReaction(type)
                        } else if (reaction.user_id === this.$attrs.auth.user.id && reaction.type !== type) {
                            this.increaseReaction(type)
                            this.currentUserSetReaction.type = type
                            this.currentUserSetReaction.isSet = true
                            this.decreaseReaction(1 - type)
                            this.sendReaction(type)
                        } else {
                            this.increaseReaction(type)
                            this.currentUserSetReaction.type = type
                            this.currentUserSetReaction.isSet = true
                            this.sendReaction(type)
                        }
                    }
                } else {
                    this.increaseReaction(type)
                    this.currentUserSetReaction.type = type
                    this.currentUserSetReaction.isSet = true
                    this.sendReaction(type)
                }
            }
        },
        increaseLike(){
            this.likes ++
        },
        increaseDislike(){
            this.dislikes ++
        },
        decreaseLike(){
            this.likes --
        },
        decreaseDislike(){
            this.dislikes --
        },
        increaseReaction(type){
            switch (type) {
                case 0:
                    this.increaseDislike()
                    break
                case 1:
                    this.increaseLike()
                    break
            }
        },
        decreaseReaction(type){
            switch (type) {
                case 0:
                    this.decreaseDislike()
                    break
                case 1:
                    this.decreaseLike()
                    break
            }
        },
        async sendReaction(type) {
            await (axios.post(route('reaction.create'), {
                id: this.$props.id,
                user_id: this.$attrs.auth.user.id,
                type: type,
            }))
        },
        async sendComment(commentData) {
            await (axios.post(route('comment.create'), {
                data: commentData,
                entity_type: 'review',
                entity_id: this.$props.id
            }));
            await this.getComments(0, true)
        },
        setOffsetAndGet(type){
            if(type === 0) {
                this.commentOffset ++;
                this.getComments(this.commentOffset);
            }
        },
        async getComments(offset, withArrayRefresh = false) {
            let response = await (axios.post(route('comment.get-for-review'), {
                'id': this.$props.id,
                'offset': offset
            }));
            if (withArrayRefresh) {
                this.comments.length = 0;
            }
            for (let item of response.data) {
                this.comments.push(item);
            }
        },
    }
}
</script>

<style scoped>
a:hover{
    color: white;
}
.stats{
    display: flex;
}
.author-review-control, .review-control{
    height: 100%;
    display: flex;
}
.ham-btn{
    margin: auto;
}
.anime-name{
    border-bottom: 1px solid var(--color-sec);
}
.name-actions-stat{
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}
.review-text{
    font-size: 1.2rem;
}
.midterm-marks{
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
}
.midterm-mark{
    margin-top: 15px;
    text-align: center;
}
.mark{
    padding: 0;
    width: fit-content;
    color: white;
    display: flex;
    flex-direction: column;
    background-color: var(--color-third);
    border-radius: 1rem;
    margin: auto;
    font-size: 1.5rem;
    text-align: center;
    overflow: hidden;
}
.mark-name{
    padding: 10px;
}
.mark-mark{
    width: 100%;
    background-color: var(--color-sec);
    padding: 3px 5px 1px 5px;
}
.final-mark{
    display: flex;
    text-align: center;
    flex-wrap: wrap;
    justify-content: center;
    flex-direction: column;
}
.final-mark-mark{
    background-color: var(--color-sec);
    padding: 10px;
}
.delete-review-modal{
    width: 50%;
}
.delete-review-modal-content{
    display: flex;
    flex-direction: column;
    width: fit-content;
    margin: 0px auto 0px auto;
    padding: 10px;
}
.btns{
    width: 10rem;
    justify-content: space-between;
    margin: auto;
    display: flex;
}
.review-author, .review-date{
    color: lightgrey;
    font-size: 1.5rem;
}
</style>

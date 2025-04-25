<template>
    <page-template :pages="pages" :user="this.$props.user">
        <template v-slot:content>
            <div class="main-content row">
                <slot name="content"></slot>
            </div>
            <div class="comment-reviews row">
                <div class="btns">
                    <btn v-if="!needReviews" class="col btn-single" @click="showCommentDiv">Комментарии</btn>
                    <btn v-else class="col btn-left" @click="showCommentDiv">Комментарии</btn>
                    <btn v-if="needReviews" class="col btn-right" @click="showReviewDiv">Рецензии</btn>
                </div>
                <div class="comments" v-if="showComments===true">
                    <div v-if="isLogin" class="comment-rules cloud">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAAELklEQVR4nO2ZS2yUVRTHf4ZpjRRUsIC6U9OIkkDksZMlL7G6MWiqWLeiFmFBIAQKS8IjbEkgNoGIkLAg8YFujILBFyAQSBB0KSBFW0oESmDMIf9LDpc7beeb6QyQ759MMvnfe+6c833ndc9Ajhw5cuR4ADAFWArsBo4C/wD9+tj337S2BJjMPYbRUv4kUCzzc0JGjRpuJduBf4G5ibWClLjoFPsL2AosBKYCTwAN+jQD04B3gW3AWSfXDSzWmTFeBfqkSybMA64DN4FXorXngcNOkW+B+cCIMs4vSMnv3DmHgJaEIUW555xyjXgW6NUBndFaK3BJa78Ds6kc9tDO6MxLeigea7TWAzwz1EPNDX6S4KfR2jt6S7bWBTRRPVicbNfZ9httbu0hYJfWDpZwwbuwVAJ/AI9GbyIYsYrhQ6dzJe/SjwF/au3jwQ550rnUrCgm+jIY8YPz//0ZjOmNYmauc7HxAx2wXhv3OK7gAtvcqRzE6bYc7JDMr1ES2St+XSnBMQq0mypsAUtcYDfV0JBRLgF85PiXpKPp+nhKcJGE9kXFLtSJslNfhYagGDG5C9FD/Fr8+yRwUIsLEoFvdYI6GGL4PhHgb4k7QIQJel0W0I84PrQdcV6vpSGtkj3uuJHAZek8zm9eoM1fOW6KazuGlLeHyZACcE7ykxLu9YbfvFnkioRbWe9EHQ0xfCJ568cCVorb5Lhbb6Ko1xiwW5w1gPU2pF3ynznudXFf+I2nRVrhCzgqzrrYehsyXfJHHPeCuFN+Y7fIsQnOWvF6GzLOpeGA5gTHNZGNg3D1MuRhyV8dhLvVoBXV+d4vhjSKMz1vo0ekdZj3i2uNEWezgLuCfWIi2O16mhX7nRFWobNiRiLYX0wF+zci7aYWEC4ydseuN96TLjsTV+B9qYJoRaaaBbFa6JIuHY5bLW6j39gm8nPHTRZ3toIWpRpoAM5LF3OnuIi/6Tc/pQbsPzVkASe02V5jvfCadDjmOGvprwA31PDegTBwaEtcqmxkkwVZr7oeBxJ91tvifiSBDi3+HN3SuhOJoFbpt1Vyf0cXq1/Ef5gSGivXsg0vO36xuDMZRpuVGDLaTU0+cPxMcZdLXXV99vIdZUETwKLmTrWqIzudh/jhw5ep9j3G027s47NBi5suxpPH4cBa/ZZ1HM8lrrh9SlADYrk2n1eHGTDfDeg6a2BEfzQ8b1as2NqyoRzU6FxpS7TW5hrMHVX+O8BiIrhTv56+xxY35/LN7YCYKOs3lBjR9LoEEE/qyZidQmD3lPgbY6O8xPeDFaNFT8YHcmuZHUCDil2oEyGwfUzUBCM0AbzgFDmnQUG7rqfNctVGfZ+hBrDLtR2hTliKLef/laqjScOz44m6MdjnmOpUNf+eqAomSbFdujtc1A3umjqEI5qEdEQNYI4cOXIwJPwP/dyzMCq1Rg4AAAAASUVORK5CYII=" alt="break--v2">
                        <btn @click="this.showRuleModal = true">Правила общения в комментариях</btn>
                    </div>
                    <div v-if="isLogin" class="comment-input">
                        <textarea @input="event => this.newCommentData.comment = event.target.value" placeholder="Напишите комментарий" rows="5"></textarea>
                        <btn @click="$emit('sendComment', this.newCommentData)" style="border-radius: 0; border: none;">
                            Отправить
                        </btn>
                    </div>
                    <template v-if="this.$props.comments.length > 0">
                        <comment @sendReply="data => this.$emit('sendReply', data)" v-for="comment in comments" :comment="comment" :current-user="this.$props.user"></comment>
                        <btn @click="$emit('moreComments')">Еще комменты</btn>
                    </template>
                    <template v-else>
                        <p style="text-align: center">
                            Тут ничего нет
                        </p>
                    </template>
                </div>
                <div class="reviews" v-if="needReviews === true && showReviews === true">
                    <template v-if="reviews.length > 0">
                        <review v-for="review in reviews" :data="review"></review>
                        <btn @click="$emit('moreReviews')">Еще рецензии</btn>
                    </template>
                    <template v-else>
                        <p style="text-align: center">
                            Тут ничего нет
                        </p>
                    </template>
                </div>
            </div>
        </template>
    </page-template>
    <modal @close="this.showRuleModal = false" :show="this.showRuleModal">
        <div class="rules-modal" style="padding: 15px;">
            <p class="h2">Правила общения в комментариях</p>
            <markdown-renderer :markdown="this.$props.rule"></markdown-renderer>
        </div>
    </modal>
</template>

<script>
import MainLayout from "@/Layouts/MainLayout.vue";
import AuthMenu from "@/Components/AuthMenu.vue";
import Review from "@/Components/Review.vue";
import Btn from "@/Components/Btn.vue";
import Comment from "@/Components/Comment.vue";
import PageTemplate from "@/Layouts/PageTemplate.vue";
import Modal from "@/Components/Modal.vue";
import MarkdownRenderer from "@/Components/MarkdownRenderer.vue";
export default {
    name: "EntityPageLayout",
    components: {MarkdownRenderer, Modal, PageTemplate, Comment, Btn, Review, AuthMenu, MainLayout},
    data(){
        return {
            isLogin: false,
            newCommentData: {
                author: 0,
                comment: '',
            },
            showComments: true,
            showReviews: false,
            showRuleModal: false,
        }
    },
    mounted() {
        if (this.$props.user !== null) {
            this.newCommentData.author = this.$props.user.id
        }
        this.isLogin = this.$props.user !== null
    },
    props:{
        user: {
            type: Object,
            default: null
        },
        pages: Array,
        needReviews: {
            type: Boolean,
            default: false,
        },
        comments: {
            type: Array,
            default: []
        },
        reviews: {
            type: Array,
            default: null
        },
        rule: {
            type: String,
            default: null,
        },
    },
    methods:{
        showReviewDiv(){
            this.showComments = false;
            this.showReviews = true;
        },
        showCommentDiv(){
            this.showComments = true;
            this.showReviews = false;
        },
    }
}
</script>

<style scoped>
.user-control{
    font-size: 1.2vw;
    width: fit-content;
}
.btns{
    display: flex;
    flex-direction: row;
}
.btn-single{
    border-radius: 0.7rem;
}
.btn-left{
    border-radius: 0.7rem 0 0 0.7rem;
}
.btn-right{
    border-radius: 0 0.7rem 0.7rem 0;
}
.comment-reviews{
    display: flex;
    flex-direction: row;
}

.comments, .reviews{
    margin-top: 20px;
}
.comment-input{
    margin-bottom: 20px;
    overflow: hidden;
    border: 1px solid var(--color-sec);
    border-radius: 1rem;
    display: flex;
    flex-direction: row;
}
textarea{
    resize: none;
    width: 100%;
    background-color: var(--color-first);
}
textarea::placeholder{
    color: white;
}
.selected{
    background-color: var(--color-sec);
}
.rules-modal{
    color: white;
    background-color: var(--color-first);
}
.comment-rules{
    display: flex;
}
.comment-rules img{
    margin: 0 10px 0 0;
}
</style>

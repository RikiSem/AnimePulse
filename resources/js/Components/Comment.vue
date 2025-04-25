<template>
    <div v-if="this.href === route('dashboard.comments')" class="my-comment">
        <div class="entity-info default-border-bottom-color-sec">
            <div class="entity-type">
                <p v-if="comment.entity.type === 'anime'">
                    Комментарий к аниме <a :href="route('anime.show', {id: comment.entity.data.id})"><span class="entity-name">{{ comment.entity.data.name }}</span></a>
                </p>
                <p v-else-if="comment.entity.type === 'review'" class="review-text">
                    Комментарий к рецензии <a :href="route('review.show', {id: comment.entity.data.id})"><span class="entity-name">{{ comment.entity.data.text }}</span></a>
                </p>
            </div>
        </div>
        <div class="comment-info">
            <div class="user-info">
                <a class="comment-author" :href="route('index')">
                    <div class="user-name">
                        {{ comment.user.name }}
                    </div>
                    <div class="user-avatar">
                        <img class="avatar" :src="comment.user.avatar" alt="упс :В">
                    </div>
                </a>
            </div>
            <div class="comment-text">
                <span ref="comment-text">{{ comment.comment }}</span>
            </div>
            <div class="comment-info">
                {{ new Date(comment.created_at).getDate() }}.
                {{ new Date(comment.created_at).getMonth() + 1 }}.
                {{ new Date(comment.created_at).getFullYear() }}
                {{ new Date(comment.created_at).getHours() }}:
                {{ new Date(comment.created_at).getMinutes() }}
            </div>
        </div>
    </div>
    <div v-else class="comment-reply default-border-bottom-color-sec" :id="`comment.${comment.id}`">
        <div class="comment">
            <div class="user-info">
                <a class="comment-author" :href="route('user', {
                    id: comment.user.id
                })">
                    <div class="user-name">
                        {{ comment.user.name }}
                    </div>
                    <div class="user-avatar">
                        <img class="avatar" :src="comment.user.avatar" alt="упс :В">
                    </div>
                </a>
            </div>
            <div class="comment-text">
                <span ref="comment-text">{{ comment.comment }}</span>
            </div>
            <div class="comment-info">
                <div class="date">
                    {{ new Date(comment.created_at).getDate() }}.
                    {{ new Date(comment.created_at).getMonth() + 1 }}.
                    {{ new Date(comment.created_at).getFullYear() }}
                    {{ new Date(comment.created_at).getHours() }}:
                    {{ new Date(comment.created_at).getMinutes() }}
                </div>
                <btn @click="this.showReplyModal = true" v-if="this.isLogin && comment.user.id !== this.$props.currentUser.id">
                    Ответить
                </btn>
            </div>
        </div>
        <div v-if="comment.replays.length > 0" class="replays">
            <btn @click="replays" style="margin-bottom: 20px;">{{ btnText }}</btn>
            <div v-show="this.replyShow" class="replays-list">
                <comment-reply @sendReply="data => this.$emit('sendReply', data)" class="reply" v-for="replay in comment.replays" :comment="replay" :is-login="this.isLogin" :current-user="this.$props.currentUser"></comment-reply>
            </div>
        </div>
    </div>
    <modal @close="this.showReplyModal = false" :show="showReplyModal">
        <div>
            Ответ пользователю {{ comment.user.name }}
            <div v-if="isLogin" class="comment-input">
                <textarea @input="event => this.newReplyData.comment = event.target.value" placeholder="Напишите комментарий" rows="5" :value="`@${comment.user.name}, `"></textarea>
                <btn @click="$emit('sendReply', this.newReplyData)" style="border-radius: 0;">
                    Отправить
                </btn>
            </div>
        </div>
    </modal>
</template>

<script>
import Btn from "@/Components/Btn.vue";
import Modal from "@/Components/Modal.vue";
import CommentReply from "@/Components/CommentReply.vue";
export default {
    name: "Comment",
    components: {CommentReply, Modal, Btn},
    data(){
        return {
            btnText: '',
            replyShow: false,
            showReplyModal: false,
            isLogin: this.$props.currentUser !== null,
            href: window.location.href,
            newReplyData: {
                author: this.$props.currentUser === null ? 0 : this.$props.currentUser.id,
                reply_to: this.$props.comment.id,
                comment: '',
            }
        }
    },
    props: {
        currentUser: null,
        comment: Object,
    },
    mounted() {
        this.replyShow = !this.replyShow
        this.replays()
    },
    methods:{
        replays(){
            this.replyShow = !this.replyShow;
            if (this.replyShow) {
                this.btnText = 'Скрыть ответы'
            } else {
                this.btnText = 'Показать ответы'
            }
        }
    }
}
</script>

<style scoped>
.user-name{
    width: fit-content;
    margin: auto;
}
.comment-author{
    margin: 15px;
    display: flex;
    flex-direction: column;
}
.user-avatar{
    margin: auto;
    width: 6rem;
    height: 6rem;
    border-radius: 50%;
    overflow: hidden;
}
.avatar{
    transform: translate(0px, -0.5rem);
}
.my-comment{
    padding: 5px;
    border-radius: 1rem;
    box-shadow: 0px 0px 10px 1px #1d1d1d;
    margin-bottom: 30px;
}
.comment{
    padding: 5px;
    border-radius: 1rem;
    box-shadow: 0px 0px 10px 1px #1d1d1d;
    margin-bottom: 30px;
    display: flex;
}
.comment-text{
    width: 100%;
    font-size: 1.2rem;
    display: flex;
    flex-direction: column;
    margin: 0 auto 0 30px;
    max-height: 150px;
    overflow-y: scroll;
}
.comment-text::-webkit-scrollbar{
    width: 10px;
    background-color: var(--color-first);
}
.comment-text::-webkit-scrollbar-thumb{
    border-radius: 10px;
    background-color: var(--color-sec);
}
.review-text{
    max-width: 20rem;
    white-space: nowrap;
    overflow: hidden;
    -webkit-line-clamp: 1;
    text-overflow: ellipsis;
}
.comment-info{
    margin: 5px;
    display: flex;
    flex-direction: column;
}
.entity-type{
    margin: 5px;
}
.entity-name{
    font-size: 2.0rem;
    text-decoration: 2px underline var(--color-sec);
}
.replays, .replays-list{
    display: flex;
    flex-direction: column;
}
.comment-reply{
    margin-bottom: 20px;
}
.reply{
    margin: 0 0 0 auto;
    width: 90%;
}
@media screen and (max-width: 950px) {
    .comment, .comment-info{
        flex-direction: column;
        justify-content: center;
    }
    .comment-text{
        margin: auto;
    }
}
</style>

<template>
    <div class="comment-reply" :id="`comment.${comment.id}`">
        <p class="reply-from-info">Ответ на коментарий <span @click="scrollTo" style="background-color: var(--color-sec)">{{ comment.replays_from.comment }}</span></p>
        <div class="comment">
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
                <comment-reply class="reply" v-for="replay in comment.replays" :comment="replay" :is-login="this.isLogin" :current-user="this.$props.currentUser"></comment-reply>
            </div>
        </div>
        <modal @close="this.showReplyModal = false" :show="showReplyModal">
            <div>
                Ответ пользователю {{ comment.user.name }}
            </div>
            <div v-if="isLogin" class="comment-input">
                <textarea @input="event => this.newReplyData.comment = event.target.value" placeholder="Напишите комментарий" rows="5" :value="`@${comment.user.name}, `"></textarea>
                <btn @click="$emit('sendReply', this.newReplyData)" style="border-radius: 0;">
                    Отправить
                </btn>
            </div>
        </modal>
    </div>
</template>

<script>
import Btn from "@/Components/Btn.vue";
import Modal from "@/Components/Modal.vue";
export default {
    name: "CommentReply",
    components: {Modal, Btn},
    data(){
        return {
            replyShow:false,
            btnText: '',
            showReplyModal: false,
            newReplyData: {
                author: this.$props.currentUser === null ? 0 : this.$props.currentUser.id,
                reply_to: this.$props.comment.id,
                comment: '',
            },
        }
    },
    props:{
        isLogin: false,
        comment: Object,
        currentUser: null
    },
    mounted() {
        this.replyShow = !this.replyShow
        this.replays()
    },
    methods:{
        scrollTo() {
            let targetHeight = document.getElementById(`comment.${this.$props.comment.replays_from.id }`).offsetTop
            window.scrollTo(0, targetHeight)
        },
        replays() {
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
.comment-info{
    margin: 5px;
    display: flex;
    flex-direction: column;
}
.reply-from-info{
    white-space: nowrap;
    overflow: hidden;
    -webkit-line-clamp: 1;
    text-overflow: ellipsis;
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

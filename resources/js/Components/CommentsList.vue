<template>
    <div class="comment-list">
        <template v-for="comment in this.$props.items">
            <p class="cloud">
                Комментарий к аниме <a class="underline-color-sec" :href="route('anime.show', {
                    id: comment.entity.data.id
                })">{{ comment.entity.data.name }}</a>
            </p>
            <comment :comment="comment" :current-user="this.$props.currentUser"></comment>
        </template>
    </div>
</template>

<script>
import Comment from "@/Components/Comment.vue";
export default {
    name: "CommentsList",
    components: {Comment},
    props: {
        items: Array,
        currentUser: Object,
    }
}
</script>

<style scoped>
.comment-list{
    width: 100%;
}
</style>

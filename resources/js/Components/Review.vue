<template>
    <div class="review">
        <div class="reviewed-anime default-border-bottom-color-sec">
            <a :href="route('review.show', {
            'id': data.id
            })">
                <h3>
                    {{ data.author.name }}
                </h3>
            </a>
            <div v-if="isAuthor" style="display: flex;flex-direction: row">
                <p>
                   Статус модерации: {{ data.status }}
                </p>
            </div>
        </div>
        <a :href="route('review.show', {
            'id': data.id
        })">
            <div class="review-text">
                <p v-html="data.html">
                </p>
            </div>
        </a>

        <div class="review-stat default-border-top-color-sec">
            <p>
                Лайков: {{ data.like_count }}
            </p>
            <p>
                Дизлайков: {{ data.dislike_count }}
            </p>
            <p>
                Комментариев: {{ data.comments_count }}
            </p>
        </div>
    </div>
</template>

<script>
export default {
    name: "Review",
    data(){
        return {
            isAuthor: this.$props.data.author.id === this.$props.currentUserId,
        }
    },
    mounted() {
        //this.isAuthor =
    },
    props:{
        data: Object,
        currentUserId: {
            type: Number,
            default: 0,
        }
    }
}
</script>

<style scoped>
a{
    color: white;
}
.review{
    display: flex;
    flex-direction: column;
    box-shadow: 0px 0px 10px 1px #1d1d1d;
    margin-bottom: 30px;
    border-radius: 1.0rem;
}
.reviewed-anime, .review-stat{
    display: flex;
    justify-content: space-between;
    flex-direction: row;
    padding: 10px;
}
.review-text{
    margin: 15px;
    font-size: 15pt;
    max-height: calc(15pt * 5 - 10px);
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}
.review-stat{
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}
</style>

<template>
    <div v-if="items.length > 0" class="list">
        <template v-for="item in items">
            <review-list-item :listItem="item"></review-list-item>
        </template>
    </div>
    <div v-else>
        <div>
            <p style="text-align: center">
                Тут пока ничего нет
            </p>
        </div>
    </div>
</template>

<script>
import ReviewListItem from "@/Components/ReviewListItem.vue";
export default {
    name: "ReviewList",
    components: {ReviewListItem},
    props: {
        items: Array
    }
}
</script>

<style scoped>
.list{
    overflow-y: scroll;
    max-height: 10rem;
}
.list::-webkit-scrollbar{
    display: none;
}
</style>

<template>
    <div class="user-page-menu">
        <a :href="route('user', {
                    id: this.$props.user.id
                })">
            <btn>Список аниме</btn>
        </a>
        <a :href="route('user.reviews', {
                    id: this.$props.user.id
                })">
            <btn>Рецензии</btn>
        </a>
        <a :href="route('user.comments', {
                    id: this.$props.user.id
                })">
            <btn>Комментарии</btn>
        </a>
    </div>
</template>

<script>
import Btn from "@/Components/Btn.vue";
export default {
    name: "UserPageMenu",
    components: {Btn},
    props:{
        user: Object
    }
}
</script>

<style scoped>
.user-page-menu a{
    margin: 0 5px 0 5px;
}
</style>

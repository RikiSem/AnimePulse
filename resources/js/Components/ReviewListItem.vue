<template>
    <a :href="route('review.show', {
       id: listItem.id
       })">
        <div class="list-item">
            <img class="user-avatar" :src="listItem.author.avatar">
            <p class="list-item-text align-self-center">
                <span>Рецензия пользователя &nbsp</span>
                <span class="username">{{ listItem.author.name }}</span>
                <span>&nbsp на аниме &nbsp</span>
                <span class="anime-name">"{{ listItem.anime.name }}"</span>
            </p>
        </div>
    </a>
</template>

<script>
export default {
    name: "ReviewListItem",
    props:{
        listItem: Object,
    }
}
</script>
<script setup>
</script>

<style scoped>
.list-item{
    display: flex;
    text-align: justify;
    padding: 5px 0px 5px 15px;
    font-size: 1rem;
    transition: 0.25s;
    max-width: 45rem;
}
.list-item-text{
    display: flex;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}
.list-item:hover{
    background-color: var(--color-sec);
}
.username{
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    background-color: var(--color-sec);
    padding: 0px 5px 0px 5px;
    max-width: 70px;
}
.anime-name{
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}
.user-avatar{
    height: 2rem;
    border-radius: 50%;
}
a:hover{
    color: white;
}
</style>

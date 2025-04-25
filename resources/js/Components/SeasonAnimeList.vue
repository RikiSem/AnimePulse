<template>
    <div id="carouselSeasonAnimeList" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <template v-for="(anime, index) in items">
                    <button v-if="index === 0" type="button" data-bs-target="#carouselSeasonAnimeList" :data-bs-slide-to=index class="active" aria-current="true" :aria-label=index></button>
                    <button v-else type="button" data-bs-target="#carouselSeasonAnimeList" :data-bs-slide-to=index :aria-label=index></button>
                </template>
            </div>
            <div class="carousel-inner">
                <template v-for="(page, index) in items">
                    <div class="carousel-item active" v-if="index === 0">
                        <div class="anime-list-page">
                            <template v-for="(anime) in page">
                                <a :href="route('anime.show', {
                                   id: anime.id
                                })">
                                    <div class="anime-item">
                                        <img :src=anime.poster class="d-block" alt="...">
                                        <h4 class="anime-name">
                                            {{ anime.name }}
                                        </h4>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>
                    <div class="carousel-item" v-else>
                        <div class="anime-list-page">
                            <template v-for="(anime) in page">
                                <a :href="route('anime.show', {
                                   id: anime.id
                                })">
                                    <div class="anime-item">
                                        <img :src=anime.poster class="d-block" alt="...">
                                        <h4 class="anime-name">
                                            {{ anime.name }}
                                        </h4>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselSeasonAnimeList" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselSeasonAnimeList" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
</template>

<script>
export default {
    name: "SeasonAnimeList",
    props:{
        items: Array,
    }
}
</script>

<style scoped>

img{
    height: clamp(10rem, 0.25rem + 2rem, 0.5rem);
}
.anime-list-page{
    display: flex;
    justify-content: space-around;
}
.carousel-inner{
    margin: auto;
    width: 80%;
}
.anime-item{
    display: flex;
    flex-direction: column;
    margin: 0px 13px 0px 13px;
    width: 8rem;
    max-height: clamp(17rem, 0.25rem + 2rem, 1rem);
}
.anime-name{
    font-size: var(--anime-name-font-size);
    position: relative;
    background-color: black;
    transform:translate(0, 0);
    overflow: hidden;
    max-width: 100%;
    text-align: center;
    white-space: nowrap;
    text-overflow: ellipsis;
}
.carousel-indicators{
    transform: translate(0, 2.5rem)
}
.carousel-control-prev, .carousel-control-next{
    width: 10%;
}
</style>

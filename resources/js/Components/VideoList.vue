<template>
    <div id="carouselAnimeVideoList" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <template v-for="(anime,index) in items">
                    <button v-if="index === 0" type="button" data-bs-target="#carouselAnimeVideoList" :data-bs-slide-to=index class="active" aria-current="true" :aria-label=index></button>
                    <button v-else type="button" data-bs-target="#carouselAnimeVideoList" :data-bs-slide-to=index :aria-label=index></button>
                </template>
            </div>
            <div class="carousel-inner">
                <template v-for="(page, index) in items">
                    <div class="carousel-item active" v-if="index === 0">
                        <div class="video-list-page">
                            <div class="video-item" v-for="(video) in page">
                                <iframe
                                    :src=video.url
                                    title="YouTube video player"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin"
                                    allowfullscreen
                                ></iframe>
                                <h4 class="anime-name">
                                    {{ video.title }}
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item" v-else>
                        <div class="video-list-page">
                            <div class="video-item" v-for="(video) in page">
                                <iframe
                                    :src=video.url
                                    title="YouTube video player"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin"
                                    allowfullscreen
                                ></iframe>
                                <h4 class="anime-name">
                                    {{ video.title }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselAnimeVideoList" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselAnimeVideoList" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
</template>

<script>
export default {
    name: "VideoList",
    props:{
        animeList: Array,
        items: Array,
    }
}
</script>

<style scoped>

iframe{
    height: clamp(19rem, 0.25rem + 2rem, 0.5rem);
    width: clamp(19rem, 0.25rem + 2rem, 0.5rem);
}
.video-list-page{
    display: flex;
    justify-content: space-around;
}
.carousel-inner{
    margin: auto;
    width: 80%;
}
.video-item{
    display: flex;
    flex-direction: column;
    max-width: 20rem;
    max-height: clamp(20rem, 0.25rem + 2rem, 1rem);
    margin: 0px 10px 0px 10px;
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

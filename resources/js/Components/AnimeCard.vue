<template>
    <a :href="route('anime.show', {
                    id: anime.id
    })">
        <div class="card default-box-shadow font-color-white">
            <div class="anime-card-img">
                <img :src="anime.poster"/>
            </div>
            <div class="anime-card-text">
                <div class="anime-info">
                    <div class="anime-name">
                        <p class="h3 font-semibold">
                            {{ anime.name }}
                        </p>
                    </div>
                    <div class="anime-rate">
                        <div>
                            <p class="font-semibold">
                                Рейтинг:&nbsp;
                            </p>
                        </div>
                        <div>
                            <p v-if="anime.rate > 0">
                                {{ anime.rate }}
                            </p>
                            <p v-else>
                                {{ anime.other_rates }}
                            </p>
                        </div>
                    </div>
                    <div class="anime-type">
                        <div>
                            <p class="font-semibold">
                                Тип:&nbsp;
                            </p>
                        </div>
                        <div>
                            <p>
                                {{ anime.type }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="tags">
                    <div class="tag" v-for="tag of anime.tags">
                        {{ tag }}
                    </div>
                </div>
                <!--<div class="desc">
                    <p>
                        {{ anime.description }}
                    </p>
                </div>-->
            </div>
            <div v-if="1==='1'">
                <a :href="route('anime.show', {
                    'id': anime.id
                })">
                    <btn class="link-to-btn">
                        <svg stroke="white" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M4 .755l14.374 11.245-14.374 11.219.619.781 15.381-12-15.391-12-.609.755z"/>
                        </svg>
                    </btn>
                </a>
            </div>
        </div>
    </a>

</template>

<script>
import Btn from "@/Components/Btn.vue";
export default {
    name: "AnimeCard",
    components: {Btn},
    props:{
        anime:{
            type: Object
        }
    },
}
</script>

<style scoped>
.card{
    background-color: var(--color-first);
    display: flex;
    flex-direction: row;
    margin: 10px;
    border-radius: 1.5rem;
    overflow: hidden;
    min-height: 25rem;
    text-overflow: ellipsis;
    width: 35.8rem;
}

.anime-card-img{
    display: flex;
    justify-content: center;
}
img{
    min-width: 18rem;
    height: 400px;
}
.anime-card-text{
    padding-left: 10px;
    font-size: 1.2rem;
    min-height: 100%;
}
.link-to-btn{
    height: 100%;
    text-align: center;
    width: 5rem;
    max-width: 2rem;
}
.tags{
    display: flex;
    flex-wrap: wrap;
    font-size: 1.0rem;
    justify-content: center;
}
td{
    padding: 5px;
}
.tag{
    margin: 5px;
    width: fit-content;
    background-color: var(--color-sec);
    padding: 10px;
}
.anime-info{
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
}
.anime-name, .anime-rate, .anime-type{
    display: flex;
    margin: 5px 7px 5px 7px;
}
.desc{
    max-height: 14rem;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 7;
    -webkit-box-orient: vertical;
    text-overflow: ellipsis;
}
@media only screen and (max-width: 1530px){
    .card{
        padding: 0px 0px 20px 0px;
        flex-wrap: wrap;
        width: 18.5rem;
    }
    .anime-card-img{
        width: 100%;
        display: flex;
        justify-content: center;
    }
    .anime-card-text{
        width: 100%;
        justify-content: center;
    }
    .desc{
        -webkit-line-clamp: 5;
    }
}
</style>

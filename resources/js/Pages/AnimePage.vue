<template>
    <Head :title="animeData.name"></Head>
    <entity-page-layout
        @moreComments="setOffsetAndGet(0)"
        @moreReviews="setOffsetAndGet(1)"
        @sendComment="data => sendComment(data)"
        @sendReply="data => sendComment(data)"
        :pages="pages"
        :user="this.$attrs.auth.user"
        :comments="this.comments"
        :reviews="this.reviews"
        :need-reviews="true"
        :rule="this.$props.rule"
    >
        <template #content>
            <div class="anime-card row">
                <div class="anime-img anime-poster-control">
                    <img class="anime-poster" :src="animeData.poster" alt="упс :B">
                    <div class="anime-control" v-if="this.isLogin === true">
                        <dropdown>
                            <template #trigger>
                                <btn tooltip="Добавить в список" class="first-btn anime-control-btn">
                                    <svg class="svg-g" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path d="M24 10h-10v-10h-4v10h-10v4h10v10h4v-10h10z"/>
                                    </svg>
                                </btn>
                            </template>
                            <template #content>
                                <btn @click="setViewStatus(status.value)" class="user-status-btn" v-for="(status, index) in user_statuses">
                                    <p v-if="animeData.current_user !== undefined && status.value === animeData.current_user.user_view" class="bg-color-green">
                                        {{ status.title }}
                                    </p>
                                    <p v-else>
                                        {{ status.title }}
                                    </p>
                                </btn>
                            </template>
                        </dropdown>
                        <template v-if="animeData.current_user !== undefined && animeData.current_user.user_favorite !== null && animeData.current_user.user_favorite > 0">
                            <btn @click="setFavorite" tooltip="Добавить в избранное" class="btn anime-control-btn bg-color-green">
                                <svg class="svg-g" clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="m14.121 19.337c-.467.453-.942.912-1.424 1.38-.194.189-.446.283-.697.283s-.503-.094-.697-.283c-4.958-4.813-9.303-8.815-9.303-12.54 0-3.348 2.582-5.177 5.234-5.177 1.831 0 3.636.867 4.766 2.563 1.125-1.688 2.935-2.554 4.771-2.554 2.649 0 5.229 1.815 5.229 5.168 0 .681-.144 1.37-.411 2.072-.375-.361-.798-.673-1.258-.925.113-.393.169-.773.169-1.147 0-2.52-1.933-3.668-3.729-3.668-1.969 0-3.204 1.355-4.159 2.694-.141.197-.369.314-.612.314-.243-.001-.471-.119-.611-.317-.953-1.347-2.165-2.699-4.155-2.7-.985 0-1.937.346-2.61.95-.735.658-1.124 1.602-1.124 2.727 0 2.738 3.046 5.842 8.5 11.127.346-.336.682-.663 1.007-.981.327.383.701.724 1.114 1.014zm3.38-9.335c2.58 0 4.499 2.107 4.499 4.499 0 2.58-2.105 4.499-4.499 4.499-2.586 0-4.5-2.112-4.5-4.499 0-2.406 1.934-4.499 4.5-4.499zm.5 3.999v-1.5c0-.265-.235-.5-.5-.5-.266 0-.5.235-.5.5v1.5h-1.5c-.266 0-.5.235-.5.5s.234.5.5.5h1.5v1.5c0 .265.234.5.5.5.265 0 .5-.235.5-.5v-1.5h1.499c.266 0 .5-.235.5-.5s-.234-.5-.5-.5z"/>
                                </svg>
                            </btn>
                        </template>
                        <template v-else>
                            <btn @click="setFavorite" tooltip="Добавить в избранное" class="btn anime-control-btn">
                                <svg class="svg-g" clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="m14.121 19.337c-.467.453-.942.912-1.424 1.38-.194.189-.446.283-.697.283s-.503-.094-.697-.283c-4.958-4.813-9.303-8.815-9.303-12.54 0-3.348 2.582-5.177 5.234-5.177 1.831 0 3.636.867 4.766 2.563 1.125-1.688 2.935-2.554 4.771-2.554 2.649 0 5.229 1.815 5.229 5.168 0 .681-.144 1.37-.411 2.072-.375-.361-.798-.673-1.258-.925.113-.393.169-.773.169-1.147 0-2.52-1.933-3.668-3.729-3.668-1.969 0-3.204 1.355-4.159 2.694-.141.197-.369.314-.612.314-.243-.001-.471-.119-.611-.317-.953-1.347-2.165-2.699-4.155-2.7-.985 0-1.937.346-2.61.95-.735.658-1.124 1.602-1.124 2.727 0 2.738 3.046 5.842 8.5 11.127.346-.336.682-.663 1.007-.981.327.383.701.724 1.114 1.014zm3.38-9.335c2.58 0 4.499 2.107 4.499 4.499 0 2.58-2.105 4.499-4.499 4.499-2.586 0-4.5-2.112-4.5-4.499 0-2.406 1.934-4.499 4.5-4.499zm.5 3.999v-1.5c0-.265-.235-.5-.5-.5-.266 0-.5.235-.5.5v1.5h-1.5c-.266 0-.5.235-.5.5s.234.5.5.5h1.5v1.5c0 .265.234.5.5.5.265 0 .5-.235.5-.5v-1.5h1.499c.266 0 .5-.235.5-.5s-.234-.5-.5-.5z"/>
                                </svg>
                            </btn>
                        </template>
                        <btn @click="showReviewCreateModal = !showReviewCreateModal" tooltip="Написать рецензию" class="btn anime-control-btn">
                            <svg class="svg-g" height="24" preserveAspectRatio="xMidYMid meet" version="1.0" viewBox="0 0 256.000000 256.000000" width="24" xmlns="http://www.w3.org/2000/svg">
                                <g stroke="none" transform="translate(0.000000,256.000000) scale(0.100000,-0.100000)">
                                    <path d="M2016 2465 c-22 -8 -53 -24 -70 -36 -35 -25 -175 -171 -345 -359 -320 -352 -690 -719 -1088 -1078 l-190 -170 -41 -105 c-66 -169 -203 -587 -200 -610 2 -13 11 -23 24 -25 21 -3 316 96 559 188 162 62 138 42 400 335 319 356 648 680 1090 1071 283 252 325 307 325 428 -1 68 -31 115 -156 237 -91 89 -128 119 -159 128 -53 14 -101 13 -149 -4z m117 -159 c46 -19 173 -154 181 -193 4 -17 2 -50 -4 -72 -12 -47 -56 -90 -420 -422 -390 -355 -503 -467 -1021 -1009 l-187 -195 -78 -29 c-44 -16 -84 -31 -91 -33 -6 -3 -14 6 -18 18 -11 32 -81 105 -116 119 -36 15 -35 23 12 135 28 67 38 79 251 280 351 332 706 689 954 960 331 362 392 423 439 440 51 18 59 18 98 1z"/>
                                </g>
                            </svg>
                        </btn>
                        <btn tooltip="Нашли баг?" class="last-btn anime-control-btn">
                            <svg class="svg-g" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                <g data-name="Search Virus">
                                    <path d="M21 30a6.008 6.008 0 0 0 5.509-3.627L29 27.618V31a1 1 0 0 0 2 0v-4a1 1 0 0 0-.553-.894l-3.466-1.734c.008-.123.019-.246.019-.372v-1.231l2.554.199 2.739 2.739a1 1 0 0 0 1.414-1.414l-3-3a.998.998 0 0 0-.629-.29L27 20.763v-1.145l3.447-1.723A1 1 0 0 0 31 17v-5a1 1 0 0 0-2 0v4.382l-2 1V17a1 1 0 0 0-1-1h-1a3.963 3.963 0 0 0-.797-2.372l.659-1.121A.998.998 0 0 0 25 12v-1a1 1 0 0 0-2 0v.728l-.37.628a3.917 3.917 0 0 0-3.26 0l-.37-.629V11a1 1 0 0 0-2 0v1a.998.998 0 0 0 .138.507l.66 1.12A3.963 3.963 0 0 0 17 16h-1a1 1 0 0 0-1 1v.382l-2-1V12a1 1 0 0 0-2 0v5a1 1 0 0 0 .553.894L15 19.619v1.145l-3.078.24a.998.998 0 0 0-.629.29l-3 3a1 1 0 0 0 1.414 1.414l2.739-2.74L15 22.77V24c0 .126.011.249.019.372l-3.466 1.733A1 1 0 0 0 11 27v4a1 1 0 0 0 2 0v-3.382l2.491-1.245A6.008 6.008 0 0 0 21 30zm4-6a3.996 3.996 0 0 1-3 3.858V18h3zm-4-10a2.002 2.002 0 0 1 2 2h-4a2.002 2.002 0 0 1 2-2zm-4 4h3v9.858A3.996 3.996 0 0 1 17 24z"/>
                                    <path d="M39 21a18 18 0 1 0-18 18 18.02 18.02 0 0 0 18-18zM5 21a16 16 0 1 1 16 16A16.018 16.018 0 0 1 5 21z"/>
                                    <path d="M61.636 50.222 46.432 35.018l1.293-1.293a1 1 0 0 0-1.415-1.415l-3.292 3.293-3.952-3.951a21.075 21.075 0 1 0-7.414 7.414l3.952 3.951-3.294 3.293a1 1 0 0 0 1.414 1.415l1.293-1.293 15.205 15.204a8.07 8.07 0 1 0 11.414-11.414zM2 21a19 19 0 1 1 19 19A19.022 19.022 0 0 1 2 21zm35.957 12.371L41.586 37 37 41.586l-3.628-3.629a21.679 21.679 0 0 0 4.585-4.586zM55.929 62a6.033 6.033 0 0 1-4.293-1.778L36.432 45.018l8.586-8.586 15.204 15.204A6.071 6.071 0 0 1 55.929 62z"/>
                                    <path d="M58.1 58.1a3.144 3.144 0 0 1-4.343.001l-3.7-3.7a1 1 0 0 0-1.413 1.415l3.699 3.7a5.071 5.071 0 0 0 7.172-.002A1 1 0 0 0 58.1 58.1z"/>
                                </g>
                            </svg>
                        </btn>
                    </div>
                </div>
                <div class="anime-info">
                    <table>
                        <tr>
                            <th>
                                Рейтинг
                            </th>
                            <td class="table-content">
                                {{animeData.rate}}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Название
                            </th>
                            <td class="table-content">
                                {{animeData.name}}
                            </td>
                        </tr>
                        <tr v-if="animeData.tags !== null">
                            <th>
                                Жанры
                            </th>
                            <td class="table-content">
                                <div class="tags">
                                    <p class="tag" v-for="tag in animeData.tags">{{tag}}</p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Сезон
                            </th>
                            <td class="table-content">
                                {{animeData.season}}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Дата выхода
                            </th>
                            <td class="table-content">
                                {{animeData.release_date}}
                            </td>
                        </tr>
                        <tr v-if="animeData.studio !== null">
                            <th>
                                Студия
                            </th>
                            <td class="table-content">
                                <div>
                                    <p v-for="studio in animeData.studio">
                                        {{studio}}
                                    </p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Статус
                            </th>
                            <td class="table-content">
                                {{animeData.status}}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Количество серий
                            </th>
                            <td class="table-content">
                                {{animeData.count_series}}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="description">
                    <p v-if="animeData.description === null">
                        Тут пока ничего нет....
                    </p>
                    <p v-else>
                        {{animeData.description}}
                    </p>
                </div>
            </div>
        </template>
    </entity-page-layout>
    <modal @close="showReviewCreateModal = !showReviewCreateModal" :show="showReviewCreateModal">
        <write-review @close="showReviewCreateModal = !showReviewCreateModal" :anime-id="this.$props.id" :user-id="this.$attrs.auth.user.id"></write-review>
    </modal>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import Modal from "@/Components/Modal.vue";
import WriteReview from "@/Components/WriteReview.vue";
import EntityPageLayout from "@/Layouts/EntityPageLayout.vue";
import Btn from "@/Components/Btn.vue";
import Dropdown from "@/Components/Dropdown.vue";
export default {
    name: "AnimePage",
    components: {
        Dropdown,
        Btn,
        EntityPageLayout,
        WriteReview, Modal, Head},
    data(){
        return {
            showComments: true,
            showReviews: false,
            animeData: [],
            reviews: [],
            comments: [],
            commentOffset: 0,
            reviewOffset: 0,
            isLogin: false,
            userId: 0,
            showReviewCreateModal: false,
        }
    },
    props:{
        id: Number,
        pages: Array,
        user_statuses: Array,
        rule: String,
    },
    mounted() {
        this.isLogin = this.$attrs.auth.user !== null;
        if (this.isLogin) {
            this.userId = this.$attrs.auth.user.id
        }
        this.getAnimeData();
        this.getComments(this.commentOffset);
        this.getReviews(this.reviewOffset);
    },
    methods:{
        async setFavorite(){
            await (axios.post(route('favorite.add'), {
                entity_type: 'anime',
                entity_id: this.$props.id,
                user_id: this.$attrs.auth.user.id
            }))
            if (this.animeData.current_user.user_favorite > 0) {
                this.animeData.current_user.user_favorite = null
            } else if (this.animeData.current_user.user_favorite === null) {
                this.animeData.current_user.user_favorite = 200
            }
        },
        async setViewStatus(status) {
            await (axios.post(route('anime.add-to-list'), {
                anime_id: this.$props.id,
                user_id: this.$attrs.auth.user.id,
                old_status: this.animeData.current_user.user_view,
                new_status: status,
            }))
            if (this.animeData.current_user.user_view === status) {
                this.animeData.current_user.user_view = null
            } else {
                this.animeData.current_user.user_view = status
            }
        },
        async sendComment(commentData) {
            await (axios.post(route('comment.create'), {
                data: commentData,
                entity_type: 'anime',
                entity_id: this.$props.id
            }));
            await this.getComments(0, true)
        },
        showReviewDiv(){
            this.showComments = false;
            this.showReviews = true;
        },
        showCommentDiv(){
            this.showComments = true;
            this.showReviews = false;
        },
        setOffsetAndGet(type){
            if(type === 0) {
                this.commentOffset ++;
                this.getComments(this.commentOffset);
            } else {
                this.reviewOffset ++;
                this.getReviews(this.reviewOffset);
            }
        },
        async getAnimeData() {
            let response = await (axios.post(route('anime.get-one'), {
                id: this.$props.id,
                user_id: this.userId,
            }));
            this.animeData = response.data;
        },
        async getComments(offset, withArrayRefresh = false) {
            let response = await (axios.post(route('comments.get-for-anime'), {
                id: this.$props.id,
                offset: offset
            }));
            if (withArrayRefresh) {
                this.comments.length = 0;
            }
            for (let item of response.data) {
                this.comments.push(item);
            }
        },
        async getReviews(offset) {
            let response = await (axios.post(route('review.get-for-anime'), {
                'id': this.$props.id,
                'offset': offset
            }));
            for (let item of response.data) {
                this.reviews.push(item);
            }
        }
    }
}
</script>

<style scoped>
.anime-control{
    /*display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: space-between;*/
    display: grid;
    grid-template-columns: 25% 25% 25% 25%;
}
.anime-card{
    justify-content: space-evenly;
    margin-bottom: 20px;
    display: flex;
    flex-wrap: wrap;
}
.anime-info{
    width: fit-content;
}
.anime-img{
    margin: 15px;
    width: fit-content;
    text-align: center;
}
.anime-poster{
    border-radius: var(--default-border-radius) var(--default-border-radius) 0 0;
    width: 20rem;
}
.tags{
    max-width: 20vw;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    font-size: 1.0rem;
}
.tag{
    margin: 5px;
    width: fit-content;
    background-color: var(--color-sec);
    padding: 10px;
}
table{
    height: 100%;
    font-size: 1.2rem;
}
.table-content{
    text-align: right;
}
.user-status-btn{
    padding: 0;
    border-radius: 0;
    width: 100%;
}
.user-status-btn p{
    padding: 5px;
}
.description{
    font-size: 1.2rem;
    text-indent: 3rem;
}
.svg-g{
    fill: white;
}
.anime-control-btn{
    border-radius: 0;
    width: 100%;
    display: flex;
}
.anime-control-btn svg{
    margin: auto;
}
.anime-control-btn:hover{
    fill: var(--color-first);
}
.anime-poster-control{
    margin: 0;
    padding: 0;
}
.first-btn{
    border-radius: 0 0 0 var(--default-border-radius);
}
.last-btn{
    border-radius: 0 0 var(--default-border-radius) 0;
}
</style>

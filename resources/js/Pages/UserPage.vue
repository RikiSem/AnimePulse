<template>
    <Head :title="String().concat('Страница пользователя ', this.$props.user.name)"></Head>
    <page-template :pages="pages" :user="this.$attrs.auth.user">
        <template #content>
            <user-page-menu :user="this.$props.user"></user-page-menu>
            <div class="content">
                <div class="user-info">
                    <div>
                        <avatar :src="this.$props.user.avatar"></avatar>
                    </div>
                    <div>
                        <table>
                            <tr>
                              <th>
                                  Ник
                              </th>
                                <td>
                                    {{ this.$props.user.name }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Дата регистрации
                                </th>
                                <td>
                                    {{ new Date(this.$props.user.created_at).toLocaleString() }}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!--<div class="btns">
                        <warning-btn class="btn">Пожаловаться</warning-btn>
                    </div>-->
                </div>
                <div class="anime-list">
                    <div class="py-3 bg-color-first">
                        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                            <div class="overflow-hidden shadow-sm">
                                <anime-list-filter @filterData="filterData => this.applyFilter(filterData)" :filter-route="'anime.filter'" :user-id="this.$attrs.auth.user.id" :statuses-for-user="statusForUser" :anime-statuses="animeStatuses" id="anime-filter" class="mx-5 my-5 default-border-color-sec p-6"></anime-list-filter>
                                <div class="my-anime-list">
                                    <my-anime-list-table :items="result"></my-anime-list-table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </page-template>
</template>

<script>
import PageTemplate from "@/Layouts/PageTemplate.vue";
import Avatar from "@/Components/Avatar.vue";
import AnimeListFilter from "@/Components/AnimeListFilter.vue";
import MyAnimeListTable from "@/Components/MyAnimeListTable.vue";
import WarningBtn from "@/Components/WarningBtn.vue";
import Btn from "@/Components/Btn.vue";
import UserPageMenu from "@/Components/UserPageMenu.vue";
import { Head } from '@inertiajs/vue3';
export default {
    name: "UserPage",
    components: {UserPageMenu, Btn, WarningBtn, MyAnimeListTable, AnimeListFilter, Avatar, PageTemplate, Head},
    data(){
        return {
            result: null,
            params: [],
        }
    },
    props: {
        user: Object,
        pages: Array,
        animeStatuses: Array,
        statusForUser: Array,
    },
    methods:{
        applyFilter(filterData){
            filterData.offset = this.offset
            this.params = filterData
            this.result = null
            this.getData()
        },
        async getData() {
            let response = await (axios.post(route('anime.get'), {
                'userId': this.$props.user.id,
                'params': this.params
            }));
            this.result = response.data;
        }
    },
    mounted() {
        this.getData();
    }
}
</script>

<style scoped>
.content{
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
}
.user-info{
    padding: 20px 30px 40px 30px;
    border-radius: 2rem;
    display: flex;
    flex-direction: column;
}
.user-info div{
    display: flex;
    flex-direction: column;
    margin: auto;
    text-align: center;
}
td, th{
    padding: 5px;
}
.user-page-menu a{
    margin: 0 5px 0 5px;
}
</style>

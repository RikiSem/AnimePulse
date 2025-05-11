<template>
    <Head title="Ваш профиль"/>
    <main-layout :pages="pages" :user="this.$attrs.auth.user">
        <template v-slot:auth-menu>
            <div class="relative user-control">
                <Dropdown align="right">
                    <template #trigger>
                        <span class="inline-flex rounded-md">
                            <button
                                type="button"
                                class="btn-border-color-sec bg-color-first inline-flex items-center btn-custom rounded-md transition duration-150 ease-in-out focus:outline-none"
                            >
                                <span class="user-menu-text">
                                    {{ $page.props.auth.user.name }}
                                </span>

                                <svg
                                    class="-me-0.5 ms-2 h-4 w-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>
                        </span>
                    </template>

                    <template #content>
                        <DropdownLink
                            class="font-color-white dd"
                            :href="route('logout')"
                            method="post"
                            as="button"
                        >
                            Выйти
                        </DropdownLink>
                    </template>
                </Dropdown>
            </div>
        </template>
        <template v-slot:body>
            <AuthenticatedLayout>
                <div class="py-3 bg-color-first">
                    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                        <div class="overflow-hidden shadow-sm">
                            <anime-list-filter
                                @filterData="filterData => this.applyFilter(filterData)"
                                :filter-route="'anime.filter'"
                                :user-id="this.$attrs.auth.user.id"
                                :statuses-for-user="statusForUser"
                                :anime-statuses="animeStatuses"
                                :anime-types="animeTypes"
                                :anime-release-years="animeReleaseYears"
                                :studios="studios"
                                id="anime-filter" class="mx-5 my-5 default-border-color-sec p-6">
                            </anime-list-filter>
                            <div class="my-anime-list">
                                <my-anime-list-table :items="result"></my-anime-list-table>
                            </div>
                        </div>
                    </div>
                </div>
            </AuthenticatedLayout>
        </template>
    </main-layout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import AnimeListFilter from "@/Components/AnimeListFilter.vue";
import MyAnimeListTable from "@/Components/MyAnimeListTable.vue";
export default {
    components: {MyAnimeListTable, AnimeListFilter, DropdownLink, Dropdown, MainLayout, AuthenticatedLayout, Head},
    data(){
        return {
            result: null,
            params: []
        }
    },
    props:{
        pages: Array,
        animeStatuses: Array,
        statusForUser: Array,
        animeTypes: Array,
        animeReleaseYears: Array,
        studios: Array,
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
                'userId': this.$attrs.auth.user.id,
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
.dd{
    font-size: 0.9rem;
}
.dd:hover{
    background-color: var(--color-sec);
    color: var(--default-font-color);
}
.user-control{
    font-size: 0.9rem;
    width: fit-content;
}
.my-anime-list{
    overflow-x: scroll;
}
.my-anime-list::-webkit-scrollbar{
    display: none;
}
</style>

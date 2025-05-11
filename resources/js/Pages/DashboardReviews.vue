<template>
    <Head title="Ваши рецензии"/>
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
                        <div class="shadow-sm">
                            <review v-for="review in result" :data="review" :current-user-id="this.$attrs.auth.user.id"></review>
                            <btn @click="setOffsetAndGet()">Еще рецензии</btn>
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
import Review from "@/Components/Review.vue";
import Btn from "@/Components/Btn.vue";
export default {
    components: {Btn, Review, MyAnimeListTable, AnimeListFilter, DropdownLink, Dropdown, MainLayout, AuthenticatedLayout, Head},
    data(){
        return {
            result: null,
            reviewOffset: 0,
        }
    },
    props:{
        pages: Array,
    },
    methods:{
        async getData() {
            let response = await (axios.post(route('user.reviews'), {
                'userId': this.$attrs.auth.user.id,
                offset: this.reviewOffset,
            }));
            this.result = response.data;
        },
        setOffsetAndGet(){
            this.reviewOffset ++;
            this.getData();
        },
    },
    mounted() {
        this.getData();
    }
}
</script>
<style scoped>
.btn-custom{
    padding: 3px;
}
.dd{
    font-size: 1.2vw;
}
h3{
    font-size: xx-large;
    margin-bottom: 10px;
}
.dd:hover{
    background-color: var(--color-sec);
    color: var(--default-font-color);
}
.user-control{
    font-size: 0.9rem;
    width: fit-content;
}
</style>

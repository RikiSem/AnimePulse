<template>
    <div class="filter">
        <div class="anime-filter">
            <check-box-list class="mx-1" @itemChanged="event => this.filterData.status = event.target.value" :items="animeStatuses">Статус аниме</check-box-list>
            <check-box-list class="mx-1" @itemChanged="event => this.filterData.type = event.target.value" :items="animeTypes">Тип аниме</check-box-list>
            <check-box-list class="mx-1" v-if="userId > 0" @itemChanged="event => this.filterData.view_status = event.target.value" :items="statusesForUser">Статус аниме у пользователя</check-box-list>
            <check-box-list class="mx-1" @itemChanged="event => this.filterData.release_year = event.target.value" :items="animeReleaseYears">Год выхода</check-box-list>
            <check-box-list class="mx-1" @itemChanged="event => this.filterData.studio = event.target.value" :items="studios">Студия</check-box-list>
            <multiple-select @selectedTags="selectedTags => this.filterData.tags = selectedTags" class="mx-1" :items="tags">Жанры</multiple-select>
            <p class="filter-checkbox"><check-box-input v-if="userId > 0" @statusChange="status => this.filterData.favoriteStatus = status" name="isFavorite">Любимое</check-box-input></p>
            <p class="filter-checkbox"><check-box-input @statusChange="status => this.filterData.newest = status" name="newest">Сначала новое</check-box-input></p>
        </div>
        <div class="my-3" style="display: flex;">
            <text-input @input="event => this.filterData.name = event.target.value" type="text" class="bg-color-first h-16 text-input align-self-center anime-name-input" placeholder="Название аниме"></text-input>
        </div>
        <div class="row">
            <btn
                style="margin-bottom: 20px;"
                v-if="JSON.stringify(this.defaultFilterData) !== JSON.stringify(this.filterData)"
                @click="dropFilterAndApply"
            >
                Сбросить фильтр
            </btn>
            <btn @click="applyFilter">Фильтровать</btn>
        </div>
    </div>

</template>

<script>
import CheckBoxList from "@/Components/CheckBoxList.vue";
import Btn from "@/Components/Btn.vue";
import TextInput from "@/Components/TextInput.vue";
import MultipleSelect from "@/Components/MultipleSelect.vue";
import CheckBoxInput from "@/Components/CheckBoxInput.vue";
export default {
    name: "AnimeListFilter",
    components: {CheckBoxInput, MultipleSelect, TextInput, Btn, CheckBoxList},
    data(){
        return {
            filterData: {
                userId: '',
                status: '',
                view_status: '',
                name: '',
                type: '',
                tags: [],
                favoriteStatus: false,
                newest: false,
                studio: '',
                release_year: '',
                offset: 0,
            },
            defaultFilterData: {
                userId: '',
                status: '',
                view_status: '',
                name: '',
                type: '',
                tags: [],
                favoriteStatus: false,
                newest: false,
                studio: '',
                release_year: '',
                offset: 0,
            },
            tags: [],
        }
    },
    props:{
        studios: {
            type: Array,
        },
        animeReleaseYears:{
            type: Array,
        },
        userId:{
            type: Number,
            default: 0,
        },
        animeTypes: {
            type: Array,
        },
        animeStatuses: {
            type: Array,
        },
        statusesForUser: {
            type: Array
        },
        filterRoute: String
    },
    mounted() {
        this.getTags()
    },
    methods:{
        dropFilterAndApply()
        {
            this.defaultFilterData.userId = this.filterData.userId
            this.filterData = this.defaultFilterData
            this.applyFilter();
        },
        async applyFilter() {
            this.filterData.userId = this.$props.userId;
            this.$emit('filterData', this.filterData)
        },
        async getTags() {
            let response = await(axios.post(route('anime.get-tags')));
            this.tags = response.data
        }
    }
}
</script>

<style scoped>
.text-input{
    height: fit-content;
}
.text-input::placeholder{
    color: white;
    font-size: 1rem;
}
.filter{
    display: flex;
    flex-direction: column;
    border-radius: 1rem;
}
.anime-filter{
    text-align: center;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
}
.anime-name-input{
    width: 100%;
    min-width: 5rem;
}
.filter-checkbox{
    margin: 5px;
}
</style>

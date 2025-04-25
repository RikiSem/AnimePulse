<template>
    <div class="filter">
        <div class="anime-filter">
            <check-box-list class="mx-1" @itemChanged="event => this.filterData.animeStatus = event.target.value" :items="animeStatuses">Статус аниме</check-box-list>
            <check-box-list class="mx-1" v-if="userId !== 0" @itemChanged="event => this.filterData.animeStatusForUser = event.target.value" :items="statusesForUser">Статус аниме у пользователя</check-box-list>
        </div>
        <div class="my-3" style="display: flex;">
            <text-input @input="event => this.filterData.animeName = event.target.value" type="text" class="bg-color-first h-16 text-input align-self-center anime-name-input" placeholder="Название аниме"></text-input>
        </div>
        <div class="row">
            <btn style="margin-bottom: 20px;" v-if="filterData.animeStatus !== '' || filterData.animeStatusForUser !== '' || filterData.animeName !== ''" @click="dropFilterAndApply">Сбросить фильтр</btn>
            <btn @click="applyFilter">Фильтровать</btn>
        </div>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import Btn from "@/Components/Btn.vue";
import CheckBoxList from "@/Components/CheckBoxList.vue";

export default {
    name: "LibraryListFilter",
    components: {TextInput, Btn, CheckBoxList},
    data(){
        return {
            filterData: {
                'userId': '',
                'animeStatus': '',
                'animeStatusForUser': '',
                'animeName': ''
            }
        }
    },
    props:{
        userId:{
            type: Number,
            default: 0
        },
        animeStatuses: {
            type: Array,
        },
        statusesForUser: {
            type: Array
        },
        filterRoute: String
    },
    methods:{
        dropFilterAndApply()
        {
            this.filterData.animeName = ''
            this.filterData.animeStatusForUser = ''
            this.filterData.animeStatus = ''
            this.applyFilter();
        },
        async applyFilter() {
            this.filterData.userId = this.$props.userId;
            let response = await(axios.post(route(this.$props.filterRoute), this.filterData));
            this.$emit('successResponse', response.data)
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
</style>

<template>
    <div class="filter-item">
        <label for="select">
            <slot></slot>
        </label>
        <div class="selected-items default-border">
            <p style="height: fit-content; margin: auto;font-size: 0.8rem;" v-if="selectedItems.length === 0">Выберите жанры</p>
            <div class="selected-item" v-for="(item, index) in selectedItems">
                <span>{{ item }}</span>
                <svg @click="deleteItem(index)" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
                    <line x1="0" x2="50" y1="0" y2="50" />
                    <line x1="0" x2="50" y1="50" y2="0" />
                </svg>
            </div>
        </div>
        <dropdown class="tags-dd">
            <template #trigger style="padding: 0px;margin: 0px">
                <btn class="my-2">Показать жанры</btn>
            </template>
            <template #content>
                <div class="default-font bg-color-first tag-list" id="select">
                    <option @click="event => selectItem(event)" class="default-font bg-color-first pointer-cursor" v-for="(item, index) in items" :value="item.title">
                        {{ item.title }}
                    </option>
                </div>
            </template>
        </dropdown>
    </div>
</template>

<script>
import Dropdown from "@/Components/Dropdown.vue";
import Btn from "@/Components/Btn.vue";
export default {
    name: "MultipleSelect",
    components: {Btn, Dropdown},
    data(){
        return {
            selectedItems: []
        }
    },
    props:{
        items: Object
    },
    methods:{
        selectItem(event){
            let index = this.selectedItems.indexOf(event.target.value)
            if (index > -1) {
                this.deleteItem(index)
            } else {
                this.selectedItems.push(event.target.value)
            }
            this.emit()
        },
        deleteItem(index) {
            this.selectedItems.splice(index, 1)
        },
        emit(){
            this.$emit('selectedTags', this.selectedItems);
        }
    }
}
</script>

<style scoped>
.filter-item{
    font-size: 1rem;
    display: flex;
    flex-direction: column;
    text-align: center;
}
.tags-dd{
    font-size: 0.75rem;
}
.tag-list{
    max-height: 15rem;
    overflow-y: scroll;
}
.selected-items{
    display: flex;
    flex-wrap: wrap;
    height: 40px;
    overflow-x: scroll;
}
.selected-items::-webkit-scrollbar{
    display: none;
}
.selected-item{
    display: flex;
    flex-direction: row;
    margin: 5px;
}
svg {
    margin: auto 0px auto 5px;
    width: 10px;
    cursor: pointer;
}

svg line {
    stroke: var(--color-sec);
    stroke-width: 5;
}

svg:hover line {
    stroke: white;
}
</style>

<template>
    <div class="filter-item">
        <label for="select">
            <slot></slot>
        </label>
        <select @change="$emit('itemChanged', $event)" class="default-font bg-color-first default-border" id="select">
            <template v-for="(item, index) in items">
                <option selected disabled class="default-font bg-color-first" v-if="index===0" :value=item.value>
                    {{ item.title }}
                </option>
                <option class="default-font bg-color-first" v-else :value="item.value">
                    {{ item.title }}
                </option>
            </template>
        </select>
    </div>
</template>

<script>
export default {
    name: "CheckBoxList",
    props:{
        items: Object
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
select{
    width: 100%;
    min-width: 100px;
    font-size: 0.75rem;
}
option{
    font-size: 0.75rem;
}
option:disabled{
    font-size: 0.75rem;
    color: #e2e2e2;
    font-weight: bold;
    background-color: var(--color-sec);
}
</style>

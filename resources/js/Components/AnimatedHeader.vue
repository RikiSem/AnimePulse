<template>
    <winter-header v-if="winterMonth.includes(currentMonth)"></winter-header>
    <spring-header v-else-if="springMonth.includes(currentMonth)"></spring-header>
    <summer-header v-else-if="summerMonth.includes(currentMonth)"></summer-header>
    <autumn-header v-else-if="autumnMonth.includes(currentMonth)"></autumn-header>
</template>

<script>
import WinterHeader from "@/Components/WinterHeader.vue";
import SpringHeader from "@/Components/SpringHeader.vue";
import SummerHeader from "@/Components/SummerHeader.vue";
import AutumnHeader from "@/Components/AutumnHeader.vue";
export default {
    name: "AnimatedHeader",
    components: {AutumnHeader, SummerHeader, SpringHeader, WinterHeader},
    props:{
        currentMonth: {
            type: Number,
            default: new Date().getMonth()+1,
        }
    },
    data(){
        return{
            winterMonth: [1,2,12],
            springMonth: [3,4,5],
            summerMonth: [6,7,8],
            autumnMonth: [9,10,11],
        }
    }
}
</script>

<style scoped>

</style>

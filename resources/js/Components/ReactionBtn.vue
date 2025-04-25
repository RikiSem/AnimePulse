<template>
    <btn :tooltip="tooltip" :disabled="disabled" @click="$emit('btnClick')" tooltip="Поставить дизлайк" v-if="this.$props.dislike" class="dislikes reaction-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 16 16" id="dislike">
            <path d="M14 10h-4v3c0 1.103-.897 2-2 2H6.5a.5.5 0 0 1-.5-.5v-2.367L4.066 8.748A.493.493 0 0 1 4 8.5v-7a.5.5 0 0 1 .5-.5h8.025a2 2 0 0 1 1.827 1.188l1.604 3.609A.491.491 0 0 1 16 6v2c0 1.103-.897 2-2 2zM0 8.5v-7A.5.5 0 0 1 .5 1H3v8H.5a.5.5 0 0 1-.5-.5z"></path>
        </svg>
        <div class="slot">
            <slot></slot>
        </div>
    </btn>
    <btn :tooltip="tooltip" :disabled="disabled" @click="$emit('btnClick')" tooltip="Поставить лайк" v-else class="likes reaction-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 16 16" id="like">
            <path d="M14 6h-4V3c0-1.103-.897-2-2-2H6.5a.5.5 0 0 0-.5.5v2.367L4.066 7.252A.493.493 0 0 0 4 7.5v7a.5.5 0 0 0 .5.5h8.025a2 2 0 0 0 1.827-1.188l1.604-3.609A.491.491 0 0 0 16 10V8c0-1.103-.897-2-2-2zM0 7.5v7a.5.5 0 0 0 .5.5H3V7H.5a.5.5 0 0 0-.5.5z"></path>
        </svg>
        <div class="slot">
            <slot></slot>
        </div>
    </btn>
</template>

<script>
import Btn from "@/Components/Btn.vue";
export default {
    name: "ReactionBtn",
    components: {Btn},
    props:{
        tooltip: '',
        disabled: false,
        dislike: {
            type: Boolean,
            default: false,
        }
    }
}
</script>

<style scoped>
.likes, .dislikes{
    border-radius: 0;
}
.likes{
    border-right: none;
    margin: 5px 0 5px 5px;
    border-radius: 0.75rem 0 0 0.75rem;
}
.dislikes{
    margin: 5px 5px 5px 0;
    border-radius: 0 0.75rem 0.75rem 0;
}
.reaction-btn{
    display: flex;
    flex-direction: row;
}
.reaction-btn svg{
    fill: white;
    margin: auto;
}
.slot{
    margin: auto 0px auto 10px;
}
</style>

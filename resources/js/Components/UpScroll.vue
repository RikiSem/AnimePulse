<template>
    <btn @click="toTop" class="up-scroll" v-if="this.scrollY >= this.showScrollY">
        <svg id="Layer_1" version="1.1" xml:space="preserve" width="32px" height="32px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M18.221,7.206l9.585,9.585c0.879,0.879,0.879,2.317,0,3.195l-0.8,0.801c-0.877,0.878-2.316,0.878-3.194,0  l-7.315-7.315l-7.315,7.315c-0.878,0.878-2.317,0.878-3.194,0l-0.8-0.801c-0.879-0.878-0.879-2.316,0-3.195l9.587-9.585  c0.471-0.472,1.103-0.682,1.723-0.647C17.115,6.524,17.748,6.734,18.221,7.206z" /></svg>
    </btn>
</template>

<script>
import Btn from "@/Components/Btn.vue";
export default {
    name: "UpScroll",
    components: {Btn},
    data(){
        return {
            scrollY: 0,
            showScrollY: 600,
        }
    },
    mounted() {
        window.addEventListener('scroll', this.scroll);
    },
    unmounted() {
        window.removeEventListener('scroll', this.scroll);
    },
    methods:{
        scroll(){
            this.scrollY = window.scrollY;
        },
        toTop(){
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    }
}
</script>

<style scoped>
.up-scroll{
    padding: 0;
    display: flex;
    position: fixed;
    z-index: 20;
    top: 90%;
    left: 5%;
}
svg{
    width: 100%;
    height: 100%;
    fill: var(--color-sec);
    padding: 10px;
}
svg:hover{
    fill: var(--color-first)
}
</style>

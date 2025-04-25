<template>
    <div class="menu" v-if="winInnerWidth > minInnerWidth">
        <a v-for="page in pages" :href=route(page.path)>
            <menu-btn class="btn" v-if="page.permission === 'admin' && this.isAdmin">
                {{ page.title }}
            </menu-btn>
            <menu-btn class="btn" v-else-if="page.permission !== 'admin'">
                {{ page.title }}
            </menu-btn>
        </a>
    </div>
    <div class="menu" v-else>
        <dropdown align="center" class="dd">
            <template #trigger>
                <menu-btn class="btn">
                    <svg
                    class="h-6 w-6"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                    </svg>
                </menu-btn>
            </template>
            <template #content>
                <div style="display: flex;flex-direction: column">
                    <a v-for="page in pages" :href=route(page.path)>
                        <menu-btn class="btn">
                            {{ page.title }}
                        </menu-btn>
                    </a>
                </div>
            </template>
        </dropdown>
    </div>
</template>

<script>
import MenuBtn from "@/Components/MenuBtn.vue";
import Dropdown from "@/Components/Dropdown.vue";
export default {
    name: "Menu",
    components: {Dropdown, MenuBtn},
    props: {
        pages: Object,
        isLogin: false,
        isAdmin: false,
    },
    data(){
        return {
            winInnerWidth: 0,
            minInnerWidth: 650
        }
    },
    mounted() {
        this.winInnerWidth = window.innerWidth;
        window.addEventListener('resize', this.onResize);
    },
    unmounted() {
        window.removeEventListener('resize', this.onResize);
    },
    methods:{
        onResize(){
            this.winInnerWidth = window.innerWidth;
        }
    }
}
</script>

<style scoped>
.menu{
    display: flex;
    flex-direction: row;
}
.btn{
    width: 100%;
}
@media only screen and (max-width: 1530px){
    .menu{
        display: flex;
    }
    .btn{
        min-height: 5rem;
    }
}
</style>

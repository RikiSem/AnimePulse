<template>
    <div class="menu" v-if="winInnerWidth > minInnerWidth">
        <template v-for="page in pages">
            <template v-if="page.dropdown.length === 0">
                <a :href=route(page.path)>
                    <menu-btn class="btn" v-if="page.permission === 'admin' && this.isAdmin">
                        {{ page.title }}
                    </menu-btn>
                    <menu-btn class="btn" v-else-if="page.permission !== 'admin'">
                        {{ page.title }}
                    </menu-btn>
                </a>
            </template>
            <template v-else>
                <dropdown align="center">
                    <template #trigger>
                        <menu-btn class="btn" v-if="page.permission === 'admin' && this.isAdmin">
                            {{ page.title }}
                        </menu-btn>
                        <menu-btn class="btn" v-else-if="page.permission !== 'admin'">
                            {{ page.title }}
                        </menu-btn>
                    </template>
                    <template #content>
                        <a v-for="subPage in page.dropdown" :href=route(subPage.path)>
                            <menu-btn class="sub-btn" v-if="subPage.permission === 'admin' && this.isAdmin">
                                {{ subPage.title }}
                            </menu-btn>
                            <menu-btn class="sub-btn" v-else-if="subPage.permission !== 'admin'">
                                {{ subPage.title }}
                            </menu-btn>
                        </a>
                    </template>
                </dropdown>
            </template>
        </template>
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
                    <template v-for="page in pages" :href=route(page.path)>
                        <template v-if="page.dropdown.length === 0">
                            <a :href=route(page.path)>
                                <menu-btn class="btn" v-if="page.permission === 'admin' && this.isAdmin">
                                    {{ page.title }}
                                </menu-btn>
                                <menu-btn class="btn" v-else-if="page.permission !== 'admin'">
                                    {{ page.title }}
                                </menu-btn>
                            </a>
                        </template>
                        <template v-else>
                            <dropdown align="center">
                                <template #trigger>
                                    <menu-btn class="btn" v-if="page.permission === 'admin' && this.isAdmin">
                                        {{ page.title }}
                                    </menu-btn>
                                    <menu-btn class="btn" v-else-if="page.permission !== 'admin'">
                                        {{ page.title }}
                                    </menu-btn>
                                </template>
                                <template #content>
                                    <a v-for="subPage in page.dropdown" :href=route(subPage.path)>
                                        <menu-btn class="sub-btn" v-if="subPage.permission === 'admin' && this.isAdmin">
                                            {{ subPage.title }}
                                        </menu-btn>
                                        <menu-btn class="sub-btn" v-else-if="subPage.permission !== 'admin'">
                                            {{ subPage.title }}
                                        </menu-btn>
                                    </a>
                                </template>
                            </dropdown>
                        </template>
                    </template>
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
.sub-btn{
    padding: 5px;
    width: 6.25rem;
}
@media only screen and (max-width: 1530px){
    .menu{
        display: flex;
    }
    .btn, .sub-btn{
        min-height: 5rem;
    }
}
</style>

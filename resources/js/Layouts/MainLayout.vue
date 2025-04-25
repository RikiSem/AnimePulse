<template>
    <section class="top">
        <div class="container header">
            <div class="header_content align-items-center">
                <Menu class="menu" :pages="pages" :is-login="this.isLogin" :is-admin="this.isAdmin"></Menu>
                <animated-header></animated-header>
                <div style="display: flex" class="align-items-center">
                    <slot :is-authed="user" name="auth-menu"></slot>
                    <application-logo></application-logo>
                </div>
            </div>
        </div>
    </section>
    <div class="container body">
        <slot name="body"></slot>
    </div>
    <div class="footer">
        <slot name="footer"></slot>
        <p>© {{ this.date.getFullYear() }} {{ host }}. Все права защищены</p>
    </div>
    <up-scroll></up-scroll>
    <notification v-if="isLogin" :user-id="this.$props.user.id"></notification>
</template>

<script>
import Menu from "@/Components/Menu.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import AnimatedHeader from "@/Components/AnimatedHeader.vue";
import AuthMenu from "@/Components/AuthMenu.vue";
import UpScroll from "@/Components/UpScroll.vue";
import Notification from "@/Components/Notification.vue";
import Btn from "@/Components/Btn.vue";
export default {
    name: "MainLayout",
    components: {Btn, Notification, UpScroll, AuthMenu, AnimatedHeader, ApplicationLogo, Menu},
    props: {
        pages: Object,
        user: {
            type: Object,
            default: null
        },
    },
    mounted() {
        this.isLogin = this.$props.user !== null;
        if (this.isLogin) {
            this.isAdmin = this.$props.user.isAdmin
        }
    },
    data(){
        return{
            isLogin: false,
            isAdmin: false,
            date: new Date(),
            host: location.hostname,
        }
    }
}
</script>

<style scoped>
.top{
    position: relative;
    margin: 1vw 0px 2vw 0px;
}
.menu{
    height: clamp(5rem, 0.25rem + 2rem, 1rem);
}
.header_content{
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}
.body{
    min-height: 100vh;
}
.footer{
    margin-top: 30px;
    background-color: var(--color-sec);
    width: 100%;
    color: var(--default-font-color);
}
p{
    font-size: 20px;
    text-align: center;
}
</style>

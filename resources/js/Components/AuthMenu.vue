<template>
    <div class="auth-menu" v-if="user !== null && user.id !== 'undefined'">
        <a :href="route('dashboard')"><menu-btn class="btn">Профиль</menu-btn></a>
    </div>
    <div class="auth-menu" v-else>
        <a :href="route('login')"><menu-btn class="btn">Войти</menu-btn></a>
        <a :href="route('register')"><menu-btn class="btn">Регистрация</menu-btn></a>
    </div>
</template>

<script>
import MenuBtn from "@/Components/MenuBtn.vue";
export default {
    name: "AuthMenu",
    components: {MenuBtn},
    props:{
        user:{
            type: Object,
            default: {}
        }
    }
}
</script>

<style scoped>
.auth-menu{
    display: flex;
    height: 100%;
}
.btn{
    height: clamp(5rem, 0.25rem + 2rem, 1rem);;
}
.dd-content{
    text-align: center;
}
.dd-content p{
    margin: 10px;
}
.auth-btn{
    background-color: var(--color-sec);
}
.auth-btn:hover{
    background-color: var(--color-first);
}
</style>

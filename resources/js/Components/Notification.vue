<template>
    <div v-if="show" class="notification">
        <p>
            {{ text }}
        </p>
    </div>
</template>

<script>
import Btn from "@/Components/Btn.vue";
export default {
    name: "Notification",
    components: {Btn},
    data(){
        return {
            show: false,
            text: '',
        }
    },
    mounted() {
        if (this.$props.userId > 0) {
            Echo.private(`App.Model.User.${this.$props.userId}.events`).listen('NotificationSent', (data) => {
                this.setContent(data.message.text)
                console.log(true)
                this.execute()
            })
        }
    },
    methods:{
        execute(){
            this.setVisible()
            setTimeout(this.hide, 3000)
        },
        setContent(content) {
            this.text = content;
        },
        setVisible()
        {
            this.show = true
        },
        hide(){
            this.show = false
        }
    },
    props:{
        userId: {
            type: Number,
            default: null
        },
    },
}
</script>

<style scoped>
.notification{
    position: fixed;
    z-index: 20;
    top: 80%;
    left: 5%;
    display: flex;
    border-radius: var(--default-border-radius);
    background-color: var(--color-third);
    color: white;
}
.notification p{
    padding: 20px;
}
</style>

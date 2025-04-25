<template>
    <div class="inner-shadow">
        <div ref="content" class="content">
            <slot></slot>
            <div class="img-shadow" ref="shadow"></div>
        </div>
    </div>
</template>

<script>
export default {
    name: "InnerBoxShadow",
    mounted() {
        window.addEventListener('resize',this.resizeShadow)


        this.$refs.test.style.backgroundImage = 'url(' + this.$props.img + ') ';
        this.resizeShadow()
    },
    methods:{
        resizeShadow()
        {
            this.$refs.shadow.style.width = (this.$refs.content.offsetWidth + 10).toString() + 'px';
            this.$refs.shadow.style.height = (this.$refs.content.offsetHeight + 20).toString() + 'px';
            console.log(this.$refs.content.style.transform)
        }
    },
    props:{
        img: String
    }
}
</script>

<style scoped>
.content{
    width: fit-content;
}
.img-shadow{
    transform: translate(0, -43rem);
    position: absolute;
    box-shadow: var(--color-first) -10px 0 50px 90px inset;
}
</style>

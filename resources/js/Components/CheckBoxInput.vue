<template>
    <div>
        <p>
            <slot></slot>
        </p>
        <input @change="invertValue" type="checkbox">
    </div>
</template>

<script>
export default {
    name: "CheckBoxInput",
    data(){
        return {
            value: false
        }
    },
    methods:{
        invertValue(){
            this.value = !this.value
            this.$emit('statusChange', this.value)
        }
    }
}
</script>

<style scoped>
input{
    height: 20px;
    width: 20px;
    border-radius: 50%;
}
</style>

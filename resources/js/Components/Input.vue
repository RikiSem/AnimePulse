<template>
    <input :value=text @input="$event => text=$event.target.value"></input>
</template>

<script>
export default {
    name: "Input",
    data(){
        return{
            text: '',
        }
    }
}
</script>

<style scoped>

</style>

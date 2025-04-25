<template>
    <div class="mark">
        <div>
            <p>
                <input style="color: white;" placeholder="Критерий" @input="event => markData.data.name = event.target.value" class="mark-name" type="text" :value="this.$props.name"/>
            </p>
            <p>
                <input style="color: white;" placeholder="Оценка" min="0" max="10" size="2" @input="event => markData.data.mark = event.target.value" class="mark-value" type="number" :value="this.$props.mark">/10
            </p>
        </div>
        <div style="margin-left: 10px; display: flex;">
            <btn @click="$emit('addMark', markData)" class="delete-mark-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                     style="fill:#FFFFFF;">
                    <path d="M 19.980469 5.9902344 A 1.0001 1.0001 0 0 0 19.292969 6.2929688 L 9 16.585938 L 5.7070312 13.292969 A 1.0001 1.0001 0 1 0 4.2929688 14.707031 L 8.2929688 18.707031 A 1.0001 1.0001 0 0 0 9.7070312 18.707031 L 20.707031 7.7070312 A 1.0001 1.0001 0 0 0 19.980469 5.9902344 z"></path>
                </svg>
            </btn>
            <btn class="delete-mark-btn" @click="$emit('delete', this.$props.id)">
                <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.11 2.697L2.698 4.11 6.586 8l-3.89 3.89 1.415 1.413L8 9.414l3.89 3.89 1.413-1.415L9.414 8l3.89-3.89-1.415-1.413L8 6.586l-3.89-3.89z"></path>
                </svg>
            </btn>
        </div>
    </div>
</template>

<script>
import Btn from "@/Components/Btn.vue";
export default {
    name: "ReviewMark",
    components: {Btn},
    data(){
        return {
            markData: {
                id: this.$props.id,
                data: {
                    name: '',
                    mark: 0,
                }
            }
        }
    },
    props:{
        id: Number,
        name: String,
        mark: Number,
    }
}
</script>

<style scoped>
.mark{
    color: white;
    display: flex;
    background-color: #666666;
    padding: 10px;
    border-radius: 1rem;
    margin: 5px;
    font-size: 1.5rem;
}
.mark-name{
    width: 10rem;
}
.mark-value{
    width: 6.5rem;
}
.mark-value, .mark-name{
    color: black;
    padding: 0;
}
input{
    font-size: 1.5rem;
    margin: 0px 5px 0px 5px;
    border: 1px solid var(--color-sec);
    background-color: #666666;
}
input::placeholder{
    font-size: 1.5rem;
    color: white;
}
.delete-mark-btn{
    margin: auto 5px auto 5px;
    height: 100%;
    padding: 0;
}
svg{
    margin: 1px;
    fill: white;
}
</style>

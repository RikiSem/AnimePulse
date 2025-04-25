<template>
    <div class="create-review-form">
        <div class="review-text-editor">
            <review-text-input @textChange="data => this.reviewData.text = data.ops" :content="this.reviewData.html"></review-text-input>
        </div>
        <div class="review-mark-editor">
            <btn @click="createMark" tooltip="Добавить оценку" class="create-mark-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M24 10h-10v-10h-4v10h-10v4h10v10h4v-10h10z"/>
                </svg>
            </btn>
            <div class="marks">
                <review-mark v-for="(mark, index) in this.reviewData.marks"
                             @addMark="markData => this.reviewData.marks[markData.id] = markData.data"
                             @delete="deleteMark"
                             :id="index"
                             :name="mark.name"
                             :mark="mark.mark"
                ></review-mark>
            </div>
        </div>
        <div>
            <btn class="my-3" @click="sendReviewData">
                Сохранить
            </btn>
        </div>
    </div>
</template>

<script>
import Btn from "@/Components/Btn.vue";
import ReviewMark from "@/Components/ReviewMark.vue";
import ReviewTextInput from "@/Components/ReviewTextInput.vue";
import {QuillDeltaToHtmlConverter} from "quill-delta-to-html";
export default {
    name: "EditReview",
    components: {ReviewTextInput, ReviewMark, Btn},
    data(){
        return {
            reviewData: {
                id: this.$props.id,
                text: '',
                html: this.$props.text,
                marks: this.$props.marks,
            },
        }
    },
    props:{
        id: Number,
        text: String,
        marks: {
            type: Object,
        }
    },
    methods:{
        createMark(){
            this.reviewData.marks.push({
                name: '',
                mark: 0,
            })
        },
        deleteMark(id){
            if (id > -1) {
                this.reviewData.marks.splice(id, 1);
            }
        },
        closeModal()
        {
            this.$emit('close');
        },
        async sendReviewData(){
            if (this.reviewData.text !== undefined) {
                let converter = new QuillDeltaToHtmlConverter(this.reviewData.text, {
                    inlineStyles: true,
                });
                this.reviewData.html = converter.convert();
            }
            await (axios.post(route('review.update'), this.reviewData))
            this.closeModal()
        }
    }
}
</script>

<style scoped>
.create-review-form{
    padding: 10px;
    min-height: 100%;
}
.review-mark-editor{
    display: flex;
    margin: auto 0 auto 0;
    border: 1px solid var(--color-sec);
    width: 100%;
    min-height: 5rem;
}
.marks{
    display: flex;
    justify-content: left;
    width: 100%;
    overflow-x: scroll;
    padding-bottom: 10px;
}
.marks::-webkit-scrollbar{
    background-color: white;
}
.marks::-webkit-scrollbar-thumb{
    background-color: var(--color-sec);
    border-radius: 1rem;
}
.create-mark-btn{
    padding: 0;
    border-radius: 0;
    border: none;
    border-right: 1px solid var(--color-sec);
    margin-right: 2rem;
}
svg{
    padding: 2px 10px 2px 10px;
    width: 100%;
    height: 100%;
    fill: var(--color-sec);
}
svg:hover{
    fill: white;
}
</style>

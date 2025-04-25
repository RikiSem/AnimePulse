<template>
    <div id="editor">
        <!--<QuillEditor :options="options" v-model:content="this.$props.content"></QuillEditor>-->
    </div>
</template>

<script>
import {Quill, QuillEditor} from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import '@vueup/vue-quill/dist/vue-quill.bubble.css';
export default {
    name: "ReviewTextInput",
    components:{
        QuillEditor
    },
    props:{
        content: {
            type: Object,
            default: null
        }
    },
    mounted() {
        this.quill = new Quill('#editor', this.options);
        if (this.$props.content !== null) {
            this.quill.setContents(this.quill.clipboard.convert(this.$props.content), 'silent');
        }
        this.quill.on('text-change', (delta, oldDelta, source) => {
            this.$emit('textChange', this.quill.getContents());
        });
    },
    data(){
        return {
            quill: {},
            options: {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        ['link', 'image'],
                        [{ 'header': 1 }, { 'header': 2 }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                        [{ 'script': 'sub'}, { 'script': 'super' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        [{ 'direction': 'rtl' }],
                        [{ 'size': ['small', false, 'large', 'huge'] }],
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'font': [] }],
                        [{ 'align': [] }],
                    ],
                },
                placeholder: 'Начинайте творить)',
                readOnly: false
            }
        }
    }
}
</script>

<style scoped>

</style>

<template>
    <div v-html="renderedMarkdown"></div>
</template>

<script>
import MarkdownIt from 'markdown-it';

export default {
    props: {
        markdown: '',
    },
    data() {
        return {
            renderedMarkdown: '',
        };
    },
    mounted() {
        const md = new MarkdownIt();

        // Parse the Markdown content
        const parsedMarkdown = md.parse(this.markdown, {});

        // Traverse the parsed Markdown and replace Vue component tags with their rendered output
        const traverse = (tokens) => {
            for (let i = 0; i < tokens.length; i++) {
                const token = tokens[i];

                if (token.type === 'container_vue_component_open') {
                    const componentName = token.info.trim();
                    const componentProps = token.meta;

                    // Create a new Vue app instance and render the component
                    const app = createApp({
                        render: () => h(componentName, componentProps),
                    });

                    // Mount the app and get the rendered HTML
                    const renderedHtml = app.mount().$el.outerHTML;

                    // Replace the component tag with the rendered HTML
                    token.type = 'html_inline';
                    token.tag = '';
                    token.content = renderedHtml;
                }

                if (token.children) {
                    traverse(token.children);
                }
            }
        };

        traverse(parsedMarkdown);

        this.renderedMarkdown = md.renderer.render(parsedMarkdown, {});
    },
};
</script>

<style scoped>

</style>

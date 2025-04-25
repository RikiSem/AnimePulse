<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'active-link default-font inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
        : 'inline-flex default-font items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out',
);
</script>

<template>
    <Link class="nav-link" :href="href" :class="classes">
        <slot />
    </Link>
</template>
<style scoped>
.nav-link{
    font-size: 1rem;
    background-color: var(--color-first);
    color: var(--default-font-color);
}
.nav-link:hover{
    background-color: var(--color-sec);
}
.active-link{
    border-bottom: 2px solid var(--color-sec);
}
</style>


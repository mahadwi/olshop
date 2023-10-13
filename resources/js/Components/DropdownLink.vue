<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <Link
        class="text-left w-full block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
    >
        <slot />
    </Link>
</template>

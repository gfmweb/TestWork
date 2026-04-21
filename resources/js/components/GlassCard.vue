<template>
    <section
        class="rounded-2xl border border-white/40 bg-white/20 p-5 shadow-xl backdrop-blur-lg"
    >
        <header class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-800">
                {{ title }}
            </h2>
            <slot name="header-extra" />
        </header>
        <slot />
    </section>
</template>

<script setup lang="ts">
defineProps<{ title: string }>();
</script>

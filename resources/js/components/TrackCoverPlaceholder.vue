<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';

interface Props {
    className?: string;
}

defineProps<Props>();
</script>

<template>
    <div class="flex h-full w-full items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800">
        <AppLogoIcon class="h-3/5 w-3/5 text-primary" />
    </div>
</template>

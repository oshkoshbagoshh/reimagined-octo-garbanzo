<script setup lang="ts">
import TrackCoverPlaceholder from '@/components/TrackCoverPlaceholder.vue';
import { onMounted, ref } from 'vue';

interface Props {
    track: {
        id: number;
        title: string;
        cover_url?: string;
    };
    size?: 'sm' | 'md' | 'lg';
    className?: string;
}

const props = withDefaults(defineProps<Props>(), {
    size: 'md',
    className: '',
});

const coverUrl = ref<string | null>(null);
const loading = ref(true);
const error = ref(false);

// Size classes
const sizeClasses = {
    sm: 'h-16 w-16',
    md: 'h-32 w-32',
    lg: 'h-48 w-48',
};

// Load cover image with fallback
onMounted(async () => {
    loading.value = true;
    error.value = false;

    try {
        // If the track already has a cover_url property, use it
        if (props.track.cover_url) {
            coverUrl.value = props.track.cover_url;
        } else {
            // Otherwise, fetch it from the API
            const response = await fetch(`/api/tracks/${props.track.id}/cover`);
            if (!response.ok) {
                throw new Error('Failed to fetch cover image');
            }
            const data = await response.json();
            coverUrl.value = data.cover_url;
        }
    } catch (e) {
        console.error('Error loading cover image:', e);
        error.value = true;
    } finally {
        loading.value = false;
    }
});

// Handle image load error
const handleImageError = () => {
    error.value = true;
};
</script>

<template>
    <div :class="['overflow-hidden rounded-lg', sizeClasses[size], className]">
        <!-- Loading state -->
        <div v-if="loading" class="flex h-full w-full items-center justify-center bg-gray-100 dark:bg-gray-800">
            <div class="h-8 w-8 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
        </div>

        <!-- Error or no cover image -->
        <TrackCoverPlaceholder v-else-if="error || !coverUrl" />

        <!-- Cover image -->
        <img v-else :src="coverUrl" :alt="`Cover for ${track.title}`" class="h-full w-full object-cover" @error="handleImageError" />
    </div>
</template>

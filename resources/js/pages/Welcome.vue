<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Head, Link } from '@inertiajs/vue3';

// Define props for the tracks data passed from the controller
defineProps<{
    tracks: {
        id: number;
        title: string;
        description: string;
        duration: number;
        cover_image: string;
        bpm: number;
        key: string;
        genres: string[];
        price: number;
        artist: {
            id: number;
            name: string;
        };
    }[];
}>();

// Format duration from seconds to MM:SS
const formatDuration = (seconds: number): string => {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
};

// Format price to display as currency
const formatPrice = (price: number): string => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};
</script>

<template>
    <Head title="TFN Artist - Discover Tracks">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="min-h-screen bg-background">
        <!-- Header with navigation -->
        <header class="container mx-auto py-4">
            <nav class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold">TFN Artist</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-sm text-muted-foreground hover:text-foreground">
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="route('login')" class="text-sm text-muted-foreground hover:text-foreground"> Log in </Link>
                        <Link :href="route('register')" class="text-sm font-medium"> Register </Link>
                        <Link :href="route('artist.signup')" class="text-sm font-medium"> ArtistSignup </Link>
                    </template>
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <section class="bg-primary/5 py-20">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 md:gap-12">
                    <div class="flex flex-col justify-center space-y-4">
                        <h1 class="text-4xl font-bold tracking-tight sm:text-5xl md:text-6xl">Discover Amazing Tracks</h1>
                        <p class="text-xl text-muted-foreground">
                            Find the perfect music for your next project. Browse our collection of high-quality tracks from talented artists.
                        </p>
                        <div class="flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4">
                            <Button size="lg" class="font-medium"> Browse All Tracks </Button>
                            <Button size="lg" variant="outline" class="font-medium"> Learn More </Button>
                        </div>
                    </div>
                    <div class="flex items-center justify-center">
                        <div class="relative h-[350px] w-[350px] overflow-hidden rounded-lg shadow-xl">
                            <img
                                src="https://images.unsplash.com/photo-1511379938547-c1f69419868d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                                alt="Music Production"
                                class="h-full w-full object-cover"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tracks Grid Section -->
        <section class="py-16">
            <div class="container mx-auto px-4">
                <div class="mb-10 text-center">
                    <h2 class="mb-2 text-3xl font-bold">Featured Tracks</h2>
                    <p class="text-muted-foreground">Explore our collection of high-quality tracks</p>
                </div>

                <!-- Tracks Grid -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <Card v-for="track in tracks" :key="track.id" class="overflow-hidden">
                        <div class="relative aspect-square overflow-hidden">
                            <img
                                :src="
                                    track.cover_image ||
                                    'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'
                                "
                                :alt="track.title"
                                class="h-full w-full object-cover transition-transform duration-300 hover:scale-105"
                            />
                        </div>
                        <CardHeader>
                            <CardTitle class="line-clamp-1">{{ track.title }}</CardTitle>
                            <CardDescription>{{ track.artist.name }}</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="flex flex-wrap gap-2">
                                <span v-for="genre in track.genres.slice(0, 2)" :key="genre" class="rounded-full bg-primary/10 px-2 py-1 text-xs">
                                    {{ genre }}
                                </span>
                            </div>
                            <div class="mt-2 flex items-center justify-between text-sm text-muted-foreground">
                                <span>{{ formatDuration(track.duration) }}</span>
                                <span>{{ track.key }}</span>
                                <span>{{ track.bpm }} BPM</span>
                            </div>
                        </CardContent>
                        <CardFooter class="flex justify-between">
                            <span class="font-medium">{{ formatPrice(track.price) }}</span>
                            <Button variant="outline" size="sm" asChild>
                                <Link :href="route('tracks.show', track.id)"> View Details </Link>
                            </Button>
                        </CardFooter>
                    </Card>
                </div>
            </div>
        </section>
    </div>
</template>

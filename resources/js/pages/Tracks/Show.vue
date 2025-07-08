<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';

// Define props for the track data passed from the controller
defineProps<{
    track: {
        id: number;
        title: string;
        description: string;
        duration: number;
        cover_image: string;
        file_path: string;
        waveform_path: string;
        bpm: number;
        key: string;
        genres: string[];
        moods: string[];
        instruments: string[];
        price: number;
        artist: {
            id: number;
            name: string;
        };
    };
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
    <Head :title="`${track.title} by ${track.artist.name} - TFN Artist`" />
    <div class="min-h-screen bg-background">
        <!-- Header with navigation -->
        <header class="container mx-auto py-4">
            <nav class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <Link :href="route('home')" class="text-2xl font-bold">TFN Artist</Link>
                </div>
                <div class="flex items-center space-x-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="text-sm text-muted-foreground hover:text-foreground"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-sm text-muted-foreground hover:text-foreground"
                        >
                            Log in
                        </Link>
                        <Link
                            :href="route('register')"
                            class="text-sm font-medium"
                        >
                            Register
                        </Link>
                    </template>
                </div>
            </nav>
        </header>

        <!-- Breadcrumbs -->
        <div class="container mx-auto px-4 py-2">
            <div class="flex items-center text-sm text-muted-foreground">
                <Link :href="route('home')" class="hover:text-foreground">Home</Link>
                <span class="mx-2">/</span>
                <span class="font-medium text-foreground">{{ track.title }}</span>
            </div>
        </div>

        <!-- Track Details -->
        <section class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <!-- Track Cover and Info -->
                <div class="lg:col-span-2">
                    <div class="mb-6 flex flex-col gap-6 md:flex-row">
                        <div class="relative aspect-square w-full max-w-md overflow-hidden rounded-lg shadow-lg md:w-1/3">
                            <img 
                                :src="track.cover_image || 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'" 
                                :alt="track.title" 
                                class="h-full w-full object-cover"
                            />
                        </div>
                        <div class="flex flex-1 flex-col justify-between md:w-2/3">
                            <div>
                                <h1 class="mb-2 text-3xl font-bold">{{ track.title }}</h1>
                                <p class="mb-4 text-xl text-muted-foreground">by {{ track.artist.name }}</p>
                                <p class="mb-6">{{ track.description }}</p>
                                
                                <div class="mb-4 flex flex-wrap gap-2">
                                    <span v-for="genre in track.genres" :key="genre" class="rounded-full bg-primary/10 px-3 py-1 text-sm">
                                        {{ genre }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                                <div>
                                    <p class="text-sm text-muted-foreground">Duration</p>
                                    <p class="font-medium">{{ formatDuration(track.duration) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground">Key</p>
                                    <p class="font-medium">{{ track.key }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground">BPM</p>
                                    <p class="font-medium">{{ track.bpm }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Waveform Placeholder -->
                    <div class="mb-8 h-32 rounded-lg bg-muted p-4">
                        <div class="flex h-full items-center justify-center">
                            <p class="text-muted-foreground">Waveform Visualization</p>
                        </div>
                    </div>
                </div>

                <!-- Purchase Card -->
                <div>
                    <Card>
                        <CardHeader>
                            <CardTitle>Purchase Track</CardTitle>
                            <CardDescription>Get instant access to this track</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="mb-4 text-center">
                                <span class="text-3xl font-bold">{{ formatPrice(track.price) }}</span>
                            </div>
                            <div class="space-y-4">
                                <Button class="w-full">Add to Cart</Button>
                                <Button variant="outline" class="w-full">License Details</Button>
                            </div>
                        </CardContent>
                        <CardFooter>
                            <p class="text-xs text-muted-foreground">
                                By purchasing, you agree to our terms and conditions.
                            </p>
                        </CardFooter>
                    </Card>
                </div>
            </div>
        </section>

        <!-- Additional Track Details -->
        <section class="container mx-auto px-4 py-8">
            <h2 class="mb-4 text-2xl font-bold">Track Details</h2>
            <Separator class="mb-6" />
            
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div>
                    <h3 class="mb-2 text-lg font-medium">Moods</h3>
                    <div class="flex flex-wrap gap-2">
                        <span v-for="mood in track.moods" :key="mood" class="rounded-full bg-secondary/50 px-3 py-1 text-sm">
                            {{ mood }}
                        </span>
                    </div>
                </div>
                
                <div>
                    <h3 class="mb-2 text-lg font-medium">Instruments</h3>
                    <div class="flex flex-wrap gap-2">
                        <span v-for="instrument in track.instruments" :key="instrument" class="rounded-full bg-secondary/50 px-3 py-1 text-sm">
                            {{ instrument }}
                        </span>
                    </div>
                </div>
                
                <div>
                    <h3 class="mb-2 text-lg font-medium">Technical Info</h3>
                    <ul class="space-y-2 text-sm">
                        <li class="flex justify-between">
                            <span class="text-muted-foreground">Format:</span>
                            <span>WAV, 44.1kHz, 24-bit</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-muted-foreground">License Type:</span>
                            <span>Standard</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-muted-foreground">Released:</span>
                            <span>2023</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Related Tracks Section (Placeholder) -->
        <section class="bg-muted/50 py-16">
            <div class="container mx-auto px-4">
                <h2 class="mb-8 text-center text-2xl font-bold">You Might Also Like</h2>
                <div class="text-center">
                    <p class="text-muted-foreground">Related tracks will be displayed here</p>
                </div>
            </div>
        </section>
    </div>
</template>
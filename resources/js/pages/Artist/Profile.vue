<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { CalendarDays, ExternalLink, Music, Share2 } from 'lucide-vue-next';

const props = defineProps<{
    artist: {
        id: number;
        name: string;
        bio: string | null;
        profile_image: string | null;
        website: string | null;
        social_links: Record<string, string> | null;
        genres: string[] | null;
    };
    tracks: Array<{
        id: number;
        title: string;
        description: string | null;
        duration: number;
        cover_url?: string;
    }>;
    shows: Array<{
        id: number;
        title: string;
        description: string | null;
        date: string;
        venue: string;
        city: string;
        country: string;
        ticket_url: string | null;
    }>;
    isOwner: boolean;
}>();

const formatDuration = (seconds: number): string => {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
};

const formatDate = (dateString: string): string => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('en-US', {
        weekday: 'short',
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    }).format(date);
};

const socialMediaIcons = {
    facebook: 'i-mdi-facebook',
    twitter: 'i-mdi-twitter',
    instagram: 'i-mdi-instagram',
    youtube: 'i-mdi-youtube',
    spotify: 'i-mdi-spotify',
    soundcloud: 'i-mdi-soundcloud',
};

const hasSocialLinks = computed(() => {
    return props.artist.social_links && Object.values(props.artist.social_links).some(link => !!link);
});

const filteredSocialLinks = computed(() => {
    if (!props.artist.social_links) return {};
    return Object.entries(props.artist.social_links)
        .filter(([, url]) => !!url)
        .reduce((acc, [platform, url]) => {
            acc[platform] = url;
            return acc;
        }, {} as Record<string, string>);
});

const hasUpcomingShows = computed(() => {
    return props.shows && props.shows.length > 0;
});

const hasTracks = computed(() => {
    return props.tracks && props.tracks.length > 0;
});
</script>

<template>
    <Head :title="artist.name" />

    <AppLayout>
        <div class="container py-8">
            <!-- Artist Header -->
            <div class="mb-8 flex flex-col md:flex-row gap-8 items-start">
                <div v-if="artist.profile_image" class="w-32 h-32 md:w-48 md:h-48 rounded-full overflow-hidden flex-shrink-0">
                    <img :src="artist.profile_image" :alt="artist.name" class="w-full h-full object-cover" />
                </div>
                <div v-else class="w-32 h-32 md:w-48 md:h-48 rounded-full bg-muted flex items-center justify-center flex-shrink-0">
                    <span class="text-4xl font-bold text-muted-foreground">{{ artist.name.charAt(0) }}</span>
                </div>

                <div class="flex-grow">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <h1 class="text-3xl md:text-4xl font-bold">{{ artist.name }}</h1>

                        <div v-if="isOwner" class="flex flex-wrap gap-2">
                            <Button :href="route('artist.profile.edit')" variant="outline">Edit Profile</Button>
                            <Button :href="route('admin.tracks.create')">Add Track</Button>
                            <Button :href="route('artist.shows.create')">Add Show</Button>
                            <Button :href="route('artist.demo-campaigns.create')" variant="outline">Create Demo Campaign</Button>
                        </div>
                    </div>

                    <div v-if="artist.genres && artist.genres.length" class="mt-2 flex flex-wrap gap-2">
                        <span v-for="genre in artist.genres" :key="genre" class="px-2 py-1 bg-muted text-muted-foreground rounded-full text-xs">
                            {{ genre }}
                        </span>
                    </div>

                    <p v-if="artist.bio" class="mt-4 text-muted-foreground">{{ artist.bio }}</p>

                    <div v-if="artist.website || hasSocialLinks" class="mt-4 flex flex-wrap gap-4">
                        <a v-if="artist.website" :href="artist.website" target="_blank" rel="noopener noreferrer" 
                           class="flex items-center gap-1 text-sm text-muted-foreground hover:text-primary">
                            <ExternalLink class="h-4 w-4" />
                            Website
                        </a>

                        <template v-if="hasSocialLinks">
                            <a v-for="(url, platform) in filteredSocialLinks" 
                               :key="platform" 
                               :href="url" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="flex items-center gap-1 text-sm text-muted-foreground hover:text-primary">
                                <component :is="socialMediaIcons[platform]" class="h-4 w-4" />
                                {{ platform.charAt(0).toUpperCase() + platform.slice(1) }}
                            </a>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Content Tabs -->
            <Tabs default-value="music" class="w-full">
                <TabsList class="grid w-full grid-cols-3">
                    <TabsTrigger value="music" class="flex items-center gap-2">
                        <Music class="h-4 w-4" />
                        Music
                    </TabsTrigger>
                    <TabsTrigger value="shows" class="flex items-center gap-2">
                        <CalendarDays class="h-4 w-4" />
                        Shows
                    </TabsTrigger>
                    <TabsTrigger value="about" class="flex items-center gap-2">
                        <Share2 class="h-4 w-4" />
                        About
                    </TabsTrigger>
                </TabsList>

                <!-- Music Tab -->
                <TabsContent value="music">
                    <div v-if="hasTracks" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <Card v-for="track in tracks" :key="track.id" class="overflow-hidden">
                            <div class="aspect-square bg-muted">
                                <img v-if="track.cover_url" :src="track.cover_url" :alt="track.title" class="w-full h-full object-cover" />
                                <div v-else class="w-full h-full flex items-center justify-center">
                                    <Music class="h-12 w-12 text-muted-foreground" />
                                </div>
                            </div>
                            <CardHeader>
                                <CardTitle>{{ track.title }}</CardTitle>
                                <CardDescription v-if="track.duration">{{ formatDuration(track.duration) }}</CardDescription>
                            </CardHeader>
                            <CardContent v-if="track.description">
                                <p class="text-sm text-muted-foreground">{{ track.description }}</p>
                            </CardContent>
                            <CardFooter>
                                <Button :href="route('tracks.show', track.id)" variant="outline" class="w-full">
                                    Listen
                                </Button>
                            </CardFooter>
                        </Card>
                    </div>
                    <div v-else class="py-12 text-center">
                        <Music class="h-12 w-12 mx-auto text-muted-foreground" />
                        <h3 class="mt-4 text-lg font-medium">No tracks yet</h3>
                        <p class="text-muted-foreground">This artist hasn't uploaded any tracks yet.</p>
                        <Button v-if="isOwner" :href="route('admin.tracks.create')" class="mt-4">
                            Add Your First Track
                        </Button>
                    </div>
                </TabsContent>

                <!-- Shows Tab -->
                <TabsContent value="shows">
                    <div v-if="hasUpcomingShows" class="space-y-6">
                        <Card v-for="show in shows" :key="show.id">
                            <CardHeader>
                                <div class="flex justify-between items-start">
                                    <div>
                                        <CardTitle>{{ show.title }}</CardTitle>
                                        <CardDescription>{{ formatDate(show.date) }}</CardDescription>
                                    </div>
                                    <Button v-if="show.ticket_url" :href="show.ticket_url" target="_blank" size="sm">
                                        Tickets
                                    </Button>
                                </div>
                            </CardHeader>
                            <CardContent>
                                <div class="flex flex-col md:flex-row justify-between">
                                    <div>
                                        <p class="font-medium">{{ show.venue }}</p>
                                        <p class="text-sm text-muted-foreground">{{ show.city }}, {{ show.country }}</p>
                                    </div>
                                    <p v-if="show.description" class="mt-2 md:mt-0 text-sm text-muted-foreground">
                                        {{ show.description }}
                                    </p>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                    <div v-else class="py-12 text-center">
                        <CalendarDays class="h-12 w-12 mx-auto text-muted-foreground" />
                        <h3 class="mt-4 text-lg font-medium">No upcoming shows</h3>
                        <p class="text-muted-foreground">This artist doesn't have any upcoming shows.</p>
                        <Button v-if="isOwner" :href="route('artist.shows.create')" class="mt-4">
                            Add Your First Show
                        </Button>
                    </div>
                </TabsContent>

                <!-- About Tab -->
                <TabsContent value="about">
                    <Card>
                        <CardHeader>
                            <CardTitle>About {{ artist.name }}</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div v-if="artist.bio" class="prose max-w-none">
                                <p>{{ artist.bio }}</p>
                            </div>
                            <div v-else class="text-muted-foreground">
                                <p>No bio available.</p>
                            </div>

                            <div v-if="artist.genres && artist.genres.length" class="mt-6">
                                <h3 class="text-lg font-medium mb-2">Genres</h3>
                                <div class="flex flex-wrap gap-2">
                                    <span v-for="genre in artist.genres" :key="genre" class="px-3 py-1 bg-muted text-muted-foreground rounded-full">
                                        {{ genre }}
                                    </span>
                                </div>
                            </div>

                            <div v-if="artist.website || hasSocialLinks" class="mt-6">
                                <h3 class="text-lg font-medium mb-2">Connect</h3>
                                <div class="flex flex-wrap gap-4">
                                    <a v-if="artist.website" :href="artist.website" target="_blank" rel="noopener noreferrer" 
                                       class="flex items-center gap-1 text-muted-foreground hover:text-primary">
                                        <ExternalLink class="h-4 w-4" />
                                        Website
                                    </a>

                                    <template v-if="hasSocialLinks">
                                        <a v-for="(url, platform) in filteredSocialLinks" 
                                           :key="platform" 
                                           :href="url" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           class="flex items-center gap-1 text-muted-foreground hover:text-primary">
                                            <component :is="socialMediaIcons[platform]" class="h-4 w-4" />
                                            {{ platform.charAt(0).toUpperCase() + platform.slice(1) }}
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>
            </Tabs>
        </div>
    </AppLayout>
</template>

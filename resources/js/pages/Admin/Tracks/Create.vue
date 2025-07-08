<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Tracks',
        href: '/admin/tracks',
    },
    {
        title: 'Create',
        href: '/admin/tracks/create',
    },
];

// Get artists for dropdown
const artists = ref<Array<{ id: number; name: string }>>([]);
const loadingArtists = ref(true);

onMounted(async () => {
    try {
        const response = await axios.get('/api/artists');
        artists.value = response.data;
    } catch (error) {
        console.error('Error loading artists:', error);
    } finally {
        loadingArtists.value = false;
    }
});

// Form
const form = useForm({
    title: '',
    description: '',
    duration: null as number | null,
    file: null as File | null,
    cover_image: null as File | null,
    bpm: null as number | null,
    key: '',
    genres: [] as string[],
    moods: [] as string[],
    instruments: [] as string[],
    price: '' as string | number,
    artist_id: '' as string | number,
});

// File preview
const audioPreview = ref<string | null>(null);
const imagePreview = ref<string | null>(null);

// Handle audio file selection
const handleAudioFileChange = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files.length > 0) {
        const file = input.files[0];
        form.file = file;

        // Create audio preview URL
        audioPreview.value = URL.createObjectURL(file);

        // Get audio duration if possible
        const audio = new Audio();
        audio.src = audioPreview.value;
        audio.onloadedmetadata = () => {
            form.duration = Math.round(audio.duration);
        };
    }
};

// Handle image file selection
const handleImageFileChange = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files.length > 0) {
        const file = input.files[0];
        form.cover_image = file;

        // Create image preview URL
        imagePreview.value = URL.createObjectURL(file);
    }
};

// Handle form submission
const submit = () => {
    form.post('/admin/tracks', {
        onSuccess: () => {
            // Clean up object URLs to prevent memory leaks
            if (audioPreview.value) URL.revokeObjectURL(audioPreview.value);
            if (imagePreview.value) URL.revokeObjectURL(imagePreview.value);
        },
    });
};

// Common genres, moods, and instruments for selection
const commonGenres = ['Pop', 'Rock', 'Hip Hop', 'R&B', 'Electronic', 'Jazz', 'Classical', 'Country', 'Folk'];
const commonMoods = ['Happy', 'Sad', 'Energetic', 'Calm', 'Romantic', 'Angry', 'Nostalgic', 'Dreamy'];
const commonInstruments = ['Guitar', 'Piano', 'Drums', 'Bass', 'Synthesizer', 'Violin', 'Saxophone', 'Trumpet'];

// Toggle selection in array
const toggleSelection = (array: string[], item: string) => {
    const index = array.indexOf(item);
    if (index === -1) {
        array.push(item);
    } else {
        array.splice(index, 1);
    }
};
</script>

<template>
    <Head title="Create Track" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Create New Track</h1>
                <Link href="/admin/tracks">
                    <Button variant="outline">Back to Tracks</Button>
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <Card>
                    <CardHeader>
                        <CardTitle>Basic Information</CardTitle>
                        <CardDescription>Enter the basic details of your track</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <Label for="title">Title</Label>
                                <Input 
                                    id="title" 
                                    v-model="form.title" 
                                    placeholder="Enter track title" 
                                    :error="form.errors.title"
                                />
                                <p v-if="form.errors.title" class="text-sm text-red-500">{{ form.errors.title }}</p>
                            </div>

                            <div class="space-y-2">
                                <Label for="artist">Artist</Label>
                                <select 
                                    id="artist" 
                                    v-model="form.artist_id" 
                                    class="w-full rounded-md border border-input bg-background px-3 py-2"
                                    :disabled="loadingArtists"
                                >
                                    <option value="" disabled>Select an artist</option>
                                    <option v-for="artist in artists" :key="artist.id" :value="artist.id">
                                        {{ artist.name }}
                                    </option>
                                </select>
                                <p v-if="form.errors.artist_id" class="text-sm text-red-500">{{ form.errors.artist_id }}</p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="description">Description</Label>
                            <Textarea 
                                id="description" 
                                v-model="form.description" 
                                placeholder="Enter track description"
                                rows="4"
                            />
                            <p v-if="form.errors.description" class="text-sm text-red-500">{{ form.errors.description }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <Label for="bpm">BPM</Label>
                                <Input 
                                    id="bpm" 
                                    v-model="form.bpm" 
                                    type="number" 
                                    placeholder="Beats per minute"
                                />
                                <p v-if="form.errors.bpm" class="text-sm text-red-500">{{ form.errors.bpm }}</p>
                            </div>

                            <div class="space-y-2">
                                <Label for="key">Musical Key</Label>
                                <Input 
                                    id="key" 
                                    v-model="form.key" 
                                    placeholder="e.g. C Major, A Minor"
                                />
                                <p v-if="form.errors.key" class="text-sm text-red-500">{{ form.errors.key }}</p>
                            </div>

                            <div class="space-y-2">
                                <Label for="price">Price ($)</Label>
                                <Input 
                                    id="price" 
                                    v-model="form.price" 
                                    type="number" 
                                    step="0.01" 
                                    placeholder="0.00"
                                />
                                <p v-if="form.errors.price" class="text-sm text-red-500">{{ form.errors.price }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Media Files</CardTitle>
                        <CardDescription>Upload your track audio and cover image</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="space-y-2">
                            <Label for="audio-file">Audio File (MP3, WAV, OGG)</Label>
                            <Input 
                                id="audio-file" 
                                type="file" 
                                accept=".mp3,.wav,.ogg" 
                                @change="handleAudioFileChange"
                            />
                            <p v-if="form.errors.file" class="text-sm text-red-500">{{ form.errors.file }}</p>

                            <div v-if="audioPreview" class="mt-4">
                                <p class="text-sm mb-2">Preview:</p>
                                <audio controls class="w-full">
                                    <source :src="audioPreview" />
                                    Your browser does not support the audio element.
                                </audio>
                                <p v-if="form.duration" class="text-sm mt-2">
                                    Duration: {{ Math.floor(form.duration / 60) }}:{{ (form.duration % 60).toString().padStart(2, '0') }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="cover-image">Cover Image</Label>
                            <Input 
                                id="cover-image" 
                                type="file" 
                                accept="image/*" 
                                @change="handleImageFileChange"
                            />
                            <p v-if="form.errors.cover_image" class="text-sm text-red-500">{{ form.errors.cover_image }}</p>

                            <div v-if="imagePreview" class="mt-4">
                                <p class="text-sm mb-2">Preview:</p>
                                <img :src="imagePreview" alt="Cover preview" class="max-w-xs rounded-md" />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Track Metadata</CardTitle>
                        <CardDescription>Categorize your track with genres, moods, and instruments</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="space-y-2">
                            <Label>Genres</Label>
                            <div class="flex flex-wrap gap-2">
                                <Button 
                                    v-for="genre in commonGenres" 
                                    :key="genre"
                                    type="button"
                                    :variant="form.genres.includes(genre) ? 'default' : 'outline'"
                                    size="sm"
                                    @click="toggleSelection(form.genres, genre)"
                                >
                                    {{ genre }}
                                </Button>
                            </div>
                            <p v-if="form.errors.genres" class="text-sm text-red-500">{{ form.errors.genres }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label>Moods</Label>
                            <div class="flex flex-wrap gap-2">
                                <Button 
                                    v-for="mood in commonMoods" 
                                    :key="mood"
                                    type="button"
                                    :variant="form.moods.includes(mood) ? 'default' : 'outline'"
                                    size="sm"
                                    @click="toggleSelection(form.moods, mood)"
                                >
                                    {{ mood }}
                                </Button>
                            </div>
                            <p v-if="form.errors.moods" class="text-sm text-red-500">{{ form.errors.moods }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label>Instruments</Label>
                            <div class="flex flex-wrap gap-2">
                                <Button 
                                    v-for="instrument in commonInstruments" 
                                    :key="instrument"
                                    type="button"
                                    :variant="form.instruments.includes(instrument) ? 'default' : 'outline'"
                                    size="sm"
                                    @click="toggleSelection(form.instruments, instrument)"
                                >
                                    {{ instrument }}
                                </Button>
                            </div>
                            <p v-if="form.errors.instruments" class="text-sm text-red-500">{{ form.errors.instruments }}</p>
                        </div>
                    </CardContent>
                </Card>

                <div class="flex justify-end gap-4">
                    <Link href="/admin/tracks">
                        <Button type="button" variant="outline">Cancel</Button>
                    </Link>
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Saving...' : 'Save Track' }}
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

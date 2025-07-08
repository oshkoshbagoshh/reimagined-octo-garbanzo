<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Checkbox } from '@/components/ui/checkbox';
import InputError from '@/components/InputError.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { LoaderCircle, Music, CalendarDays } from 'lucide-vue-next';

const props = defineProps<{
    artist: {
        id: number;
        name: string;
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
        date: string;
        venue: string;
        city: string;
        country: string;
    }>;
}>();

const form = useForm({
    recipients: [''],
    subject: `New Music from ${props.artist.name}`,
    message: `Hi there,\n\nI wanted to share some of my latest music with you. Check out my featured tracks and upcoming shows below.\n\nThanks for your support!\n${props.artist.name}`,
    featured_track_ids: [] as number[],
    upcoming_show_ids: [] as number[],
});

const addRecipient = () => {
    form.recipients.push('');
};

const removeRecipient = (index: number) => {
    form.recipients.splice(index, 1);
};

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

const toggleTrack = (trackId: number) => {
    const index = form.featured_track_ids.indexOf(trackId);
    if (index === -1) {
        form.featured_track_ids.push(trackId);
    } else {
        form.featured_track_ids.splice(index, 1);
    }
};

const toggleShow = (showId: number) => {
    const index = form.upcoming_show_ids.indexOf(showId);
    if (index === -1) {
        form.upcoming_show_ids.push(showId);
    } else {
        form.upcoming_show_ids.splice(index, 1);
    }
};

const submit = () => {
    form.post(route('artist.demo-campaigns.send'), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Create Demo Campaign" />

    <AppLayout>
        <div class="container py-8">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl font-bold mb-8">Create Demo Email Campaign</h1>

                <form @submit.prevent="submit">
                    <Card class="mb-8">
                        <CardHeader>
                            <CardTitle>Email Details</CardTitle>
                            <CardDescription>Compose your demo campaign email</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-6">
                            <div>
                                <Label>Recipients</Label>
                                <div v-for="(recipient, index) in form.recipients" :key="index" class="flex items-center gap-2 mt-2">
                                    <Input 
                                        type="email" 
                                        v-model="form.recipients[index]" 
                                        required 
                                        placeholder="email@example.com" 
                                    />
                                    <Button 
                                        type="button" 
                                        variant="outline" 
                                        size="icon" 
                                        @click="removeRecipient(index)"
                                        :disabled="form.recipients.length === 1"
                                    >
                                        &times;
                                    </Button>
                                </div>
                                <Button type="button" variant="outline" size="sm" @click="addRecipient" class="mt-2">
                                    Add Recipient
                                </Button>
                                <InputError :message="form.errors.recipients" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="subject">Subject</Label>
                                <Input id="subject" v-model="form.subject" required />
                                <InputError :message="form.errors.subject" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="message">Message</Label>
                                <Textarea id="message" v-model="form.message" rows="6" required />
                                <InputError :message="form.errors.message" />
                            </div>
                        </CardContent>
                    </Card>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <!-- Featured Tracks -->
                        <Card>
                            <CardHeader>
                                <CardTitle class="flex items-center gap-2">
                                    <Music class="h-5 w-5" />
                                    Featured Tracks
                                </CardTitle>
                                <CardDescription>Select tracks to feature in your email</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div v-if="tracks.length === 0" class="text-center py-4 text-muted-foreground">
                                    You don't have any tracks yet.
                                </div>
                                <div v-else class="space-y-4">
                                    <div v-for="track in tracks" :key="track.id" class="flex items-center gap-3">
                                        <Checkbox 
                                            :id="`track-${track.id}`" 
                                            :checked="form.featured_track_ids.includes(track.id)"
                                            @update:checked="toggleTrack(track.id)"
                                        />
                                        <div class="flex items-center gap-3 flex-grow">
                                            <div class="w-10 h-10 bg-muted flex-shrink-0">
                                                <img v-if="track.cover_url" :src="track.cover_url" :alt="track.title" class="w-full h-full object-cover" />
                                                <div v-else class="w-full h-full flex items-center justify-center">
                                                    <Music class="h-5 w-5 text-muted-foreground" />
                                                </div>
                                            </div>
                                            <div>
                                                <Label :for="`track-${track.id}`" class="font-medium">{{ track.title }}</Label>
                                                <p v-if="track.duration" class="text-xs text-muted-foreground">{{ formatDuration(track.duration) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <InputError :message="form.errors.featured_track_ids" />
                            </CardContent>
                        </Card>

                        <!-- Upcoming Shows -->
                        <Card>
                            <CardHeader>
                                <CardTitle class="flex items-center gap-2">
                                    <CalendarDays class="h-5 w-5" />
                                    Upcoming Shows
                                </CardTitle>
                                <CardDescription>Select shows to feature in your email</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div v-if="shows.length === 0" class="text-center py-4 text-muted-foreground">
                                    You don't have any upcoming shows.
                                </div>
                                <div v-else class="space-y-4">
                                    <div v-for="show in shows" :key="show.id" class="flex items-start gap-3">
                                        <Checkbox 
                                            :id="`show-${show.id}`" 
                                            :checked="form.upcoming_show_ids.includes(show.id)"
                                            @update:checked="toggleShow(show.id)"
                                        />
                                        <div>
                                            <Label :for="`show-${show.id}`" class="font-medium">{{ show.title }}</Label>
                                            <p class="text-xs text-muted-foreground">{{ formatDate(show.date) }}</p>
                                            <p class="text-xs text-muted-foreground">{{ show.venue }}, {{ show.city }}</p>
                                        </div>
                                    </div>
                                </div>
                                <InputError :message="form.errors.upcoming_show_ids" />
                            </CardContent>
                        </Card>
                    </div>

                    <div class="flex justify-end gap-4">
                        <Button type="button" variant="outline" :href="route('artist.profile')">
                            Cancel
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin mr-2" />
                            Send Campaign
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

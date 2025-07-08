<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

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
}>();

const form = useForm({
    name: props.artist.name,
    bio: props.artist.bio || '',
    website: props.artist.website || '',
    social_links: props.artist.social_links || {
        facebook: '',
        twitter: '',
        instagram: '',
        youtube: '',
        spotify: '',
        soundcloud: '',
    },
    genres: props.artist.genres || [],
    profile_image: null as File | null,
});

const submit = () => {
    form.post(route('artist.profile.update'), {
        preserveScroll: true,
        forceFormData: true,
    });
};

const handleImageUpload = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files.length > 0) {
        form.profile_image = input.files[0];
    }
};
</script>

<template>
    <Head title="Edit Profile" />

    <AppLayout>
        <div class="container py-8">
            <div class="mx-auto max-w-3xl">
                <h1 class="mb-8 text-3xl font-bold">Edit Artist Profile</h1>

                <form @submit.prevent="submit">
                    <Card class="mb-8">
                        <CardHeader>
                            <CardTitle>Basic Information</CardTitle>
                            <CardDescription>Update your artist profile information</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-2">
                                <Label for="name">Artist/Band Name</Label>
                                <Input id="name" v-model="form.name" required />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="bio">Bio</Label>
                                <Textarea id="bio" v-model="form.bio" rows="6" placeholder="Tell us about yourself or your band" />
                                <InputError :message="form.errors.bio" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="website">Website</Label>
                                <Input id="website" type="url" v-model="form.website" placeholder="https://yourwebsite.com" />
                                <InputError :message="form.errors.website" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="profile_image">Profile Image</Label>
                                <Input id="profile_image" type="file" accept="image/*" @change="handleImageUpload" />
                                <InputError :message="form.errors.profile_image" />

                                <div v-if="artist.profile_image" class="mt-2">
                                    <p class="mb-2 text-sm text-muted-foreground">Current Image:</p>
                                    <img :src="artist.profile_image" alt="Current profile image" class="h-32 w-32 rounded-full object-cover" />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card class="mb-8">
                        <CardHeader>
                            <CardTitle>Social Media</CardTitle>
                            <CardDescription>Connect your social media accounts</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-2">
                                <Label for="facebook">Facebook</Label>
                                <Input id="facebook" type="url" v-model="form.social_links.facebook" placeholder="https://facebook.com/yourpage" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="twitter">Twitter</Label>
                                <Input id="twitter" type="url" v-model="form.social_links.twitter" placeholder="https://twitter.com/yourhandle" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="instagram">Instagram</Label>
                                <Input
                                    id="instagram"
                                    type="url"
                                    v-model="form.social_links.instagram"
                                    placeholder="https://instagram.com/yourhandle"
                                />
                            </div>

                            <div class="grid gap-2">
                                <Label for="youtube">YouTube</Label>
                                <Input id="youtube" type="url" v-model="form.social_links.youtube" placeholder="https://youtube.com/yourchannel" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="spotify">Spotify</Label>
                                <Input
                                    id="spotify"
                                    type="url"
                                    v-model="form.social_links.spotify"
                                    placeholder="https://open.spotify.com/artist/yourid"
                                />
                            </div>

                            <div class="grid gap-2">
                                <Label for="soundcloud">SoundCloud</Label>
                                <Input
                                    id="soundcloud"
                                    type="url"
                                    v-model="form.social_links.soundcloud"
                                    placeholder="https://soundcloud.com/yourhandle"
                                />
                            </div>
                        </CardContent>
                    </Card>

                    <div class="flex justify-end gap-4">
                        <Button type="button" variant="outline" :href="route('artist.profile')"> Cancel </Button>
                        <Button type="submit" :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                            Save Changes
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

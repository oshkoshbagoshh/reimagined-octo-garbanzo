<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    artist_name: '',
    bio: '',
    website: '',
    social_links: {
        facebook: '',
        twitter: '',
        instagram: '',
        youtube: '',
        spotify: '',
        soundcloud: '',
    },
    genres: [],
});

const submit = () => {
    form.post(route('artist.signup'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase title="Create an Artist Account" description="Enter your details below to create your artist account">
        <Head title="Artist Signup" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <!-- User Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium">Account Information</h3>

                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input
                            id="name"
                            type="text"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="name"
                            v-model="form.name"
                            placeholder="Full name"
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input
                            id="email"
                            type="email"
                            required
                            :tabindex="2"
                            autocomplete="email"
                            v-model="form.email"
                            placeholder="email@example.com"
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password">Password</Label>
                        <Input
                            id="password"
                            type="password"
                            required
                            :tabindex="3"
                            autocomplete="new-password"
                            v-model="form.password"
                            placeholder="Password"
                        />
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password_confirmation">Confirm password</Label>
                        <Input
                            id="password_confirmation"
                            type="password"
                            required
                            :tabindex="4"
                            autocomplete="new-password"
                            v-model="form.password_confirmation"
                            placeholder="Confirm password"
                        />
                        <InputError :message="form.errors.password_confirmation" />
                    </div>
                </div>

                <!-- Artist Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium">Artist Information</h3>

                    <div class="grid gap-2">
                        <Label for="artist_name">Artist/Band Name</Label>
                        <Input id="artist_name" type="text" required :tabindex="5" v-model="form.artist_name" placeholder="Artist or Band Name" />
                        <InputError :message="form.errors.artist_name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="bio">Bio</Label>
                        <Textarea id="bio" :tabindex="6" v-model="form.bio" placeholder="Tell us about yourself or your band" />
                        <InputError :message="form.errors.bio" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="website">Website</Label>
                        <Input id="website" type="url" :tabindex="7" v-model="form.website" placeholder="https://yourwebsite.com" />
                        <InputError :message="form.errors.website" />
                    </div>

                    <!-- Social Media Links -->
                    <div class="space-y-2">
                        <Label>Social Media Links</Label>

                        <div class="grid gap-2">
                            <Label for="facebook" class="text-sm">Facebook</Label>
                            <Input
                                id="facebook"
                                type="url"
                                :tabindex="8"
                                v-model="form.social_links.facebook"
                                placeholder="https://facebook.com/yourpage"
                            />
                        </div>

                        <div class="grid gap-2">
                            <Label for="twitter" class="text-sm">Twitter</Label>
                            <Input
                                id="twitter"
                                type="url"
                                :tabindex="9"
                                v-model="form.social_links.twitter"
                                placeholder="https://twitter.com/yourhandle"
                            />
                        </div>

                        <div class="grid gap-2">
                            <Label for="instagram" class="text-sm">Instagram</Label>
                            <Input
                                id="instagram"
                                type="url"
                                :tabindex="10"
                                v-model="form.social_links.instagram"
                                placeholder="https://instagram.com/yourhandle"
                            />
                        </div>

                        <div class="grid gap-2">
                            <Label for="youtube" class="text-sm">YouTube</Label>
                            <Input
                                id="youtube"
                                type="url"
                                :tabindex="11"
                                v-model="form.social_links.youtube"
                                placeholder="https://youtube.com/yourchannel"
                            />
                        </div>

                        <div class="grid gap-2">
                            <Label for="spotify" class="text-sm">Spotify</Label>
                            <Input
                                id="spotify"
                                type="url"
                                :tabindex="12"
                                v-model="form.social_links.spotify"
                                placeholder="https://open.spotify.com/artist/yourid"
                            />
                        </div>

                        <div class="grid gap-2">
                            <Label for="soundcloud" class="text-sm">SoundCloud</Label>
                            <Input
                                id="soundcloud"
                                type="url"
                                :tabindex="13"
                                v-model="form.social_links.soundcloud"
                                placeholder="https://soundcloud.com/yourhandle"
                            />
                        </div>
                    </div>
                </div>

                <Button type="submit" class="mt-4 w-full" tabindex="14" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Create Artist Account
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Already have an account?
                <TextLink :href="route('login')" class="underline underline-offset-4" :tabindex="15">Log in</TextLink>
            </div>
        </form>
    </AuthBase>
</template>

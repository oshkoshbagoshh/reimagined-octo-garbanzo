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
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    title: '',
    description: '',
    date: '',
    venue: '',
    city: '',
    country: '',
    ticket_url: '',
    is_featured: false,
});

const submit = () => {
    form.post(route('artist.shows.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Add Show" />

    <AppLayout>
        <div class="container py-8">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-3xl font-bold mb-8">Add New Show</h1>

                <form @submit.prevent="submit">
                    <Card>
                        <CardHeader>
                            <CardTitle>Show Details</CardTitle>
                            <CardDescription>Add information about your upcoming show</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-2">
                                <Label for="title">Title</Label>
                                <Input id="title" v-model="form.title" required placeholder="e.g. Live at The Venue" />
                                <InputError :message="form.errors.title" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="description">Description</Label>
                                <Textarea id="description" v-model="form.description" rows="3" placeholder="Additional details about the show" />
                                <InputError :message="form.errors.description" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="date">Date and Time</Label>
                                <Input id="date" type="datetime-local" v-model="form.date" required />
                                <InputError :message="form.errors.date" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="venue">Venue</Label>
                                <Input id="venue" v-model="form.venue" required placeholder="e.g. The Music Hall" />
                                <InputError :message="form.errors.venue" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="grid gap-2">
                                    <Label for="city">City</Label>
                                    <Input id="city" v-model="form.city" required placeholder="e.g. New York" />
                                    <InputError :message="form.errors.city" />
                                </div>

                                <div class="grid gap-2">
                                    <Label for="country">Country</Label>
                                    <Input id="country" v-model="form.country" required placeholder="e.g. USA" />
                                    <InputError :message="form.errors.country" />
                                </div>
                            </div>

                            <div class="grid gap-2">
                                <Label for="ticket_url">Ticket URL</Label>
                                <Input id="ticket_url" type="url" v-model="form.ticket_url" placeholder="https://ticketing-site.com/your-event" />
                                <InputError :message="form.errors.ticket_url" />
                            </div>

                            <div class="flex items-center space-x-2">
                                <Checkbox id="is_featured" v-model="form.is_featured" />
                                <Label for="is_featured">Feature this show on your profile</Label>
                                <InputError :message="form.errors.is_featured" />
                            </div>
                        </CardContent>
                    </Card>

                    <div class="flex justify-end gap-4 mt-6">
                        <Button type="button" variant="outline" :href="route('artist.profile')">
                            Cancel
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin mr-2" />
                            Add Show
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
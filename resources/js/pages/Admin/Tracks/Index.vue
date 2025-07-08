<script setup lang="ts">
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import TrackCover from '@/components/TrackCover.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

// Define props
defineProps<{
    tracks: {
        data: Array<{
            id: number;
            title: string;
            description: string | null;
            duration: number | null;
            file_path: string;
            cover_image: string | null;
            price: number;
            artist: {
                id: number;
                name: string;
            };
            created_at: string;
        }>;
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
    };
}>();

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
];

// Track to delete
const trackToDelete = ref<number | null>(null);

// Format duration from seconds to MM:SS
const formatDuration = (seconds: number | null): string => {
    if (!seconds) return '00:00';
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
};

// Format price
const formatPrice = (price: number): string => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};

// Format date
const formatDate = (dateString: string): string => {
    return new Date(dateString).toLocaleDateString();
};

// Delete track
const deleteTrack = () => {
    if (trackToDelete.value) {
        router.delete(`/admin/tracks/${trackToDelete.value}`, {
            onSuccess: () => {
                trackToDelete.value = null;
            },
        });
    }
};
</script>

<template>
    <Head title="Manage Tracks" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Manage Tracks</h1>
                <Link href="/admin/tracks/create">
                    <Button>Add New Track</Button>
                </Link>
            </div>

            <Table>
                <TableCaption>A list of your tracks</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead>Cover</TableHead>
                        <TableHead>Title</TableHead>
                        <TableHead>Artist</TableHead>
                        <TableHead>Duration</TableHead>
                        <TableHead>Price</TableHead>
                        <TableHead>Created</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="track in tracks.data" :key="track.id">
                        <TableCell>
                            <TrackCover :track="track" size="sm" />
                        </TableCell>
                        <TableCell class="font-medium">{{ track.title }}</TableCell>
                        <TableCell>{{ track.artist.name }}</TableCell>
                        <TableCell>{{ formatDuration(track.duration) }}</TableCell>
                        <TableCell>{{ formatPrice(track.price) }}</TableCell>
                        <TableCell>{{ formatDate(track.created_at) }}</TableCell>
                        <TableCell class="text-right">
                            <DropdownMenu>
                                <DropdownMenuTrigger asChild>
                                    <Button variant="ghost" size="sm"> Actions </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end">
                                    <DropdownMenuItem asChild>
                                        <Link :href="`/admin/tracks/${track.id}/edit`"> Edit </Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem @click="trackToDelete = track.id"> Delete </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="tracks.data.length === 0">
                        <TableCell colspan="6" class="py-8 text-center text-gray-500">
                            No tracks found. Click "Add New Track" to create one.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <!-- Pagination links -->
            <div class="mt-4 flex justify-center">
                <div class="flex space-x-2">
                    <template v-for="(link, i) in tracks.links" :key="i">
                        <Link v-if="link.url" :href="link.url" class="rounded border px-4 py-2" :class="{ 'bg-primary text-white': link.active }">
                            {{ link.label === '&laquo; Previous' ? '← Previous' : link.label === 'Next &raquo;' ? 'Next →' : link.label }}
                        </Link>
                        <span v-else class="rounded border px-4 py-2 text-gray-400">
                            {{ link.label === '&laquo; Previous' ? '← Previous' : link.label === 'Next &raquo;' ? 'Next →' : link.label }}
                        </span>
                    </template>
                </div>
            </div>
        </div>
    </AppLayout>

    <!-- Delete Confirmation Dialog -->
    <AlertDialog :open="trackToDelete !== null" @update:open="trackToDelete = null">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Are you sure?</AlertDialogTitle>
                <AlertDialogDescription>
                    This action cannot be undone. This will permanently delete the track and all associated files.
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>Cancel</AlertDialogCancel>
                <AlertDialogAction @click="deleteTrack" class="bg-red-500 hover:bg-red-600">Delete</AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>

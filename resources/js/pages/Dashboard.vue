<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="mb-4 text-2xl font-bold">Admin Dashboard</h1>

            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <Card>
                    <CardHeader>
                        <CardTitle>Track Management</CardTitle>
                        <CardDescription>Upload and manage your music tracks</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <p>Add, edit, and delete tracks. Upload audio files and manage track metadata.</p>
                    </CardContent>
                    <CardFooter>
                        <Link href="/admin/tracks">
                            <Button> Manage Tracks </Button>
                        </Link>
                    </CardFooter>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Analytics</CardTitle>
                        <CardDescription>Track performance metrics</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <p>View statistics and performance data for your tracks.</p>
                    </CardContent>
                    <CardFooter>
                        <Button disabled>Coming Soon</Button>
                    </CardFooter>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>User Management</CardTitle>
                        <CardDescription>Manage user accounts</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <p>Add, edit, and delete user accounts. Manage permissions.</p>
                    </CardContent>
                    <CardFooter>
                        <Button disabled>Coming Soon</Button>
                    </CardFooter>
                </Card>
            </div>

            <div class="mt-8">
                <Card>
                    <CardHeader>
                        <CardTitle>Recent Activity</CardTitle>
                        <CardDescription>Latest actions and updates</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <p class="py-8 text-center text-gray-500">No recent activity to display</p>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

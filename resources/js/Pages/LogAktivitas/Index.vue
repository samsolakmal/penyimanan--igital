<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const dummyLogs = [
    { id: 1, user_name: 'Super Admin', action: 'Upload Arsip', description: 'Mengupload file "Laporan_2024.pdf"', created_at: '2026-07-06 10:30:00' },
    { id: 2, user_name: 'Admin', action: 'Edit Arsip', description: 'Mengedit arsip "Foto_Kegiatan.jpg"', created_at: '2026-07-06 09:15:00' },
    { id: 3, user_name: 'User', action: 'Hapus Arsip', description: 'Menghapus arsip "Video_Presentasi.mp4"', created_at: '2026-07-05 16:45:00' },
];
</script>

<template>
    <Head title="Log Aktivitas" />
    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Log Aktivitas</h1>
                    <p class="mt-1 text-sm text-gray-600">Monitoring semua aktivitas pengguna</p>
                </div>

                <div class="space-y-4">
                    <div v-for="log in dummyLogs" :key="log.id" class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-semibold text-gray-900">{{ log.user_name }}</h3>
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">{{ log.action }}</span>
                                </div>
                                <p class="mt-1 text-sm text-gray-600">{{ log.description }}</p>
                                <div class="mt-2 text-xs text-gray-500">
                                    <i class="fas fa-clock mr-1"></i> {{ log.created_at }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="dummyLogs.length === 0" class="flex flex-col items-center justify-center rounded-xl border border-gray-200 bg-white py-12">
                        <i class="fas fa-clock-rotate-left text-4xl text-gray-300"></i>
                        <p class="mt-2 text-sm text-gray-500">Belum ada aktivitas</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
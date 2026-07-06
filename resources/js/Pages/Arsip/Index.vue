<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({ 
    arsip: Array 
});

const activeTab = ref('semua');
const searchQuery = ref('');
const previewItem = ref(null);

// Filter berdasarkan kategori dan pencarian
const filteredArsip = computed(() => {
    let result = props.arsip;
    
    if (activeTab.value !== 'semua') {
        result = result.filter(item => item.tipe_arsip === activeTab.value);
    }
    
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(item => item.nama_arsip.toLowerCase().includes(query));
    }
    
    return result;
});

const formatSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
};

const openPreview = (item) => {
    previewItem.value = item;
};

const closePreview = () => {
    previewItem.value = null;
};

const getFileUrl = (path) => {
    return `/storage/${path}`;
};
</script>

<template>
    <Head title="Data Arsip" />
    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Data Arsip</h1>
                    <p class="mt-1 text-sm text-gray-600">Kelola dan lihat semua arsip digital Anda</p>
                </div>

                <!-- Search & Tabs -->
                <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex gap-2 overflow-x-auto pb-2 sm:pb-0">
                        <button @click="activeTab = 'semua'" :class="activeTab === 'semua' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'" class="rounded-lg border px-4 py-2 text-sm font-medium transition-all whitespace-nowrap">Semua</button>
                        <button @click="activeTab = 'gambar'" :class="activeTab === 'gambar' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'" class="rounded-lg border px-4 py-2 text-sm font-medium transition-all whitespace-nowrap"><i class="fas fa-image mr-1"></i> Gambar</button>
                        <button @click="activeTab = 'video'" :class="activeTab === 'video' ? 'bg-purple-600 text-white border-purple-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'" class="rounded-lg border px-4 py-2 text-sm font-medium transition-all whitespace-nowrap"><i class="fas fa-video mr-1"></i> Video</button>
                        <button @click="activeTab = 'file'" :class="activeTab === 'file' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'" class="rounded-lg border px-4 py-2 text-sm font-medium transition-all whitespace-nowrap"><i class="fas fa-file-alt mr-1"></i> Dokumen</button>
                    </div>

                    <div class="relative w-full sm:w-72">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input v-model="searchQuery" type="text" placeholder="Cari nama arsip..." class="block w-full rounded-lg border border-gray-300 bg-white py-2 pl-10 pr-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                    </div>
                </div>

                <!-- Grid Cards -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div v-for="item in filteredArsip" :key="item.id" class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition-all hover:shadow-md">
                        
                        <!-- Thumbnail / Icon Area -->
                        <div class="flex h-40 items-center justify-center bg-gray-50">
                            <img v-if="item.tipe_arsip === 'gambar'" :src="getFileUrl(item.path_file)" class="h-full w-full object-cover" />
                            <div v-else-if="item.tipe_arsip === 'video'" class="text-purple-500">
                                <i class="fas fa-play-circle text-6xl"></i>
                            </div>
                            <div v-else class="text-blue-500">
                                <i class="fas fa-file-alt text-6xl"></i>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <div class="mb-2 flex items-start justify-between">
                                <h3 class="text-sm font-semibold text-gray-900 line-clamp-1" :title="item.nama_arsip">{{ item.nama_arsip }}</h3>
                                <span :class="{
                                    'bg-green-100 text-green-700': item.tipe_arsip === 'gambar',
                                    'bg-purple-100 text-purple-700': item.tipe_arsip === 'video',
                                    'bg-blue-100 text-blue-700': item.tipe_arsip === 'file'
                                }" class="ml-2 inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium capitalize whitespace-nowrap">
                                    {{ item.tipe_arsip }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mb-4">
                                <i class="fas fa-database mr-1"></i> {{ formatSize(item.ukuran_file) }} &bull; {{ new Date(item.created_at).toLocaleDateString('id-ID') }}
                            </p>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <button @click="openPreview(item)" class="flex-1 inline-flex items-center justify-center gap-1 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-medium text-blue-700 transition-all hover:bg-blue-100">
                                    <i class="fas fa-eye"></i> Lihat
                                </button>
                                <a :href="getFileUrl(item.path_file)" target="_blank" class="flex-1 inline-flex items-center justify-center gap-1 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-medium text-gray-700 transition-all hover:bg-gray-100">
                                    <i class="fas fa-download"></i> Unduh
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="filteredArsip.length === 0" class="flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 bg-white py-16">
                    <i class="fas fa-folder-open text-5xl text-gray-300"></i>
                    <p class="mt-4 text-sm font-medium text-gray-500">Tidak ada arsip ditemukan</p>
                </div>

            </div>
        </div>

        <!-- PREVIEW MODAL -->
        <div v-if="previewItem" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4 backdrop-blur-sm" @click.self="closePreview">
            <div class="relative w-full max-w-4xl overflow-hidden rounded-2xl bg-white shadow-2xl">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ previewItem.nama_arsip }}</h3>
                        <p class="text-xs text-gray-500 capitalize">{{ previewItem.tipe_arsip }} &bull; {{ formatSize(previewItem.ukuran_file) }}</p>
                    </div>
                    <button @click="closePreview" class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-gray-200 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body (Preview Area) -->
                <div class="flex max-h-[70vh] items-center justify-center bg-gray-900 p-4">
                    
                    <!-- Preview Gambar -->
                    <img v-if="previewItem.tipe_arsip === 'gambar'" :src="getFileUrl(previewItem.path_file)" class="max-h-[65vh] max-w-full rounded-lg object-contain" />
                    
                    <!-- Preview Video -->
                    <video v-else-if="previewItem.tipe_arsip === 'video'" controls class="max-h-[65vh] max-w-full rounded-lg">
                        <source :src="getFileUrl(previewItem.path_file)" />
                        Browser Anda tidak mendukung tag video.
                    </video>
                    
                    <!-- Preview Dokumen/File (PDF, dll) -->
                    <div v-else class="flex flex-col items-center justify-center text-white">
                        <i class="fas fa-file-alt text-6xl mb-4 text-blue-400"></i>
                        <p class="mb-4 text-sm">Preview tidak tersedia untuk format ini.</p>
                        <a :href="getFileUrl(previewItem.path_file)" target="_blank" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700">
                            <i class="fas fa-external-link-alt mr-2"></i> Buka di Tab Baru
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
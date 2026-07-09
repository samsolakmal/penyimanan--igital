<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({ arsip: Array });
const activeTab = ref('semua');
const searchQuery = ref('');
const previewItem = ref(null);

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

const openPreview = (item) => previewItem.value = item;
const closePreview = () => previewItem.value = null;
const getFileUrl = (path) => `/storage/${path}`;
</script>

<template>
    <Head title="Data Arsip" />
    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                
                <div class="mb-6">
                    <h1 class="text-3xl font-extrabold text-white">Data Arsip</h1>
                    <p class="mt-1 text-sm text-slate-400">Kelola dan lihat semua arsip digital Anda</p>
                </div>

                <!-- Search & Tabs -->
                <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex gap-2 overflow-x-auto pb-2 sm:pb-0">
                        <button @click="activeTab = 'semua'" :class="activeTab === 'semua' ? 'bg-blue-600 text-white border-blue-600 shadow-lg shadow-blue-500/20' : 'bg-slate-800 text-slate-300 border-slate-700 hover:bg-slate-700'" class="rounded-lg border px-4 py-2 text-sm font-medium transition-all whitespace-nowrap">Semua</button>
                        <button @click="activeTab = 'gambar'" :class="activeTab === 'gambar' ? 'bg-green-600 text-white border-green-600 shadow-lg shadow-green-500/20' : 'bg-slate-800 text-slate-300 border-slate-700 hover:bg-slate-700'" class="rounded-lg border px-4 py-2 text-sm font-medium transition-all whitespace-nowrap"><i class="fas fa-image mr-1"></i> Gambar</button>
                        <button @click="activeTab = 'video'" :class="activeTab === 'video' ? 'bg-purple-600 text-white border-purple-600 shadow-lg shadow-purple-500/20' : 'bg-slate-800 text-slate-300 border-slate-700 hover:bg-slate-700'" class="rounded-lg border px-4 py-2 text-sm font-medium transition-all whitespace-nowrap"><i class="fas fa-video mr-1"></i> Video</button>
                        <button @click="activeTab = 'file'" :class="activeTab === 'file' ? 'bg-orange-600 text-white border-orange-600 shadow-lg shadow-orange-500/20' : 'bg-slate-800 text-slate-300 border-slate-700 hover:bg-slate-700'" class="rounded-lg border px-4 py-2 text-sm font-medium transition-all whitespace-nowrap"><i class="fas fa-file-alt mr-1"></i> Dokumen</button>
                    </div>

                    <div class="relative w-full sm:w-72">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-slate-500"></i>
                        </div>
                        <input v-model="searchQuery" type="text" placeholder="Cari nama arsip..." class="block w-full rounded-lg border border-slate-700 bg-slate-800 py-2 pl-10 pr-3 text-white placeholder-slate-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all" />
                    </div>
                </div>

                <!-- Grid Cards -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div v-for="item in filteredArsip" :key="item.id" class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl shadow-black/20 transition-all hover:border-blue-500/30 hover:shadow-blue-500/10">
                        
                        <div class="flex h-40 items-center justify-center bg-slate-900/50 border-b border-slate-700">
                            <img v-if="item.tipe_arsip === 'gambar'" :src="getFileUrl(item.path_file)" class="h-full w-full object-cover" />
                            <div v-else-if="item.tipe_arsip === 'video'" class="text-purple-400">
                                <i class="fas fa-play-circle text-6xl"></i>
                            </div>
                            <div v-else class="text-orange-400">
                                <i class="fas fa-file-alt text-6xl"></i>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="mb-2 flex items-start justify-between">
                                <h3 class="text-sm font-semibold text-white line-clamp-1" :title="item.nama_arsip">{{ item.nama_arsip }}</h3>
                                <span :class="{
                                    'bg-green-500/10 text-green-400 border-green-500/20': item.tipe_arsip === 'gambar',
                                    'bg-purple-500/10 text-purple-400 border-purple-500/20': item.tipe_arsip === 'video',
                                    'bg-orange-500/10 text-orange-400 border-orange-500/20': item.tipe_arsip === 'file'
                                }" class="ml-2 inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium capitalize whitespace-nowrap">
                                    {{ item.tipe_arsip }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 mb-4">
                                <i class="fas fa-database mr-1"></i> {{ formatSize(item.ukuran_file) }} &bull; {{ new Date(item.created_at).toLocaleDateString('id-ID') }}
                            </p>

                            <div class="flex gap-2">
                                <button @click="openPreview(item)" class="flex-1 inline-flex items-center justify-center gap-1 rounded-lg border border-blue-500/30 bg-blue-500/10 px-3 py-2 text-xs font-medium text-blue-400 transition-all hover:bg-blue-500/20">
                                    <i class="fas fa-eye"></i> Lihat
                                </button>
                                <a :href="getFileUrl(item.path_file)" target="_blank" class="flex-1 inline-flex items-center justify-center gap-1 rounded-lg border border-slate-600 bg-slate-700/50 px-3 py-2 text-xs font-medium text-slate-300 transition-all hover:bg-slate-700 hover:text-white">
                                    <i class="fas fa-download"></i> Unduh
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="filteredArsip.length === 0" class="flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-700 bg-slate-800/50 py-16">
                    <i class="fas fa-folder-open text-5xl text-slate-600"></i>
                    <p class="mt-4 text-sm font-medium text-slate-400">Tidak ada arsip ditemukan</p>
                </div>

            </div>
        </div>

        <!-- PREVIEW MODAL (Dark Mode) -->
        <div v-if="previewItem" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4 backdrop-blur-sm" @click.self="closePreview">
            <div class="relative w-full max-w-4xl overflow-hidden rounded-2xl bg-slate-800 border border-slate-700 shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-700 bg-slate-900 px-6 py-4">
                    <div>
                        <h3 class="text-lg font-bold text-white">{{ previewItem.nama_arsip }}</h3>
                        <p class="text-xs text-slate-400 capitalize">{{ previewItem.tipe_arsip }} &bull; {{ formatSize(previewItem.ukuran_file) }}</p>
                    </div>
                    <button @click="closePreview" class="rounded-lg p-2 text-slate-400 transition-colors hover:bg-slate-700 hover:text-white">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="flex max-h-[70vh] items-center justify-center bg-black p-4">
                    <img v-if="previewItem.tipe_arsip === 'gambar'" :src="getFileUrl(previewItem.path_file)" class="max-h-[65vh] max-w-full rounded-lg object-contain" />
                    <video v-else-if="previewItem.tipe_arsip === 'video'" controls class="max-h-[65vh] max-w-full rounded-lg">
                        <source :src="getFileUrl(previewItem.path_file)" />
                    </video>
                    <div v-else class="flex flex-col items-center justify-center text-white">
                        <i class="fas fa-file-alt text-6xl mb-4 text-orange-400"></i>
                        <p class="mb-4 text-sm text-slate-300">Preview tidak tersedia untuk format ini.</p>
                        <a :href="getFileUrl(previewItem.path_file)" target="_blank" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-500">
                            <i class="fas fa-external-link-alt mr-2"></i> Buka di Tab Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
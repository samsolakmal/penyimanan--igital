<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Swal from 'sweetalert2';

const props = defineProps({ 
    statistik: Object, 
    arsip: Array, 
    grafik_harian: Array, 
    user_role: String 
});

const userName = usePage().props.auth.user.name;
const hariList = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

// State untuk klik detail
const selectedDay = ref(null);
const selectedCategory = ref(null);

// State untuk modal upload
const showUploadModal = ref(false);
const uploadForm = ref({ nama_arsip: '', tipe_arsip: 'gambar', file: null });
const uploading = ref(false);

// Hitung max untuk bar chart
const maxGrafik = computed(() => Math.max(...props.grafik_harian, 1));

// Hitung persentase untuk Donut Chart Kategori
const totalKategori = computed(() => 
    props.statistik.gambar + props.statistik.video + props.statistik.dokumen || 1
);
const persenGambar = computed(() => (props.statistik.gambar / totalKategori.value) * 100);
const persenVideo = computed(() => (props.statistik.video / totalKategori.value) * 100);
const persenDokumen = computed(() => (props.statistik.dokumen / totalKategori.value) * 100);

// Fungsi klik bar harian
const selectDay = (index) => { selectedDay.value = index; };

// Fungsi klik kategori
const selectCategory = (cat) => { selectedCategory.value = cat; };

// Fungsi handle file input
const handleFileChange = (event) => {
    uploadForm.value.file = event.target.files[0];
};

// Fungsi submit upload
const submitUpload = () => {
    if (!uploadForm.value.nama_arsip || !uploadForm.value.file) {
        Swal.fire({
            title: 'Gagal!',
            text: 'Nama arsip dan file wajib diisi.',
            icon: 'error',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }

    uploading.value = true;

    const formData = new FormData();
    formData.append('nama_arsip', uploadForm.value.nama_arsip);
    formData.append('tipe_arsip', uploadForm.value.tipe_arsip);
    formData.append('file', uploadForm.value.file);

    router.post(route('arsip.store'), formData, {
        forceFormData: true,
        onSuccess: () => {
            showUploadModal.value = false;
            uploadForm.value = { nama_arsip: '', tipe_arsip: 'gambar', file: null };
            uploading.value = false;
            Swal.fire({
                title: 'Berhasil!',
                text: 'Arsip berhasil ditambahkan.',
                icon: 'success',
                confirmButtonColor: '#3b82f6',
                timer: 2000,
                showConfirmButton: false
            });
        },
        onError: (errors) => {
            uploading.value = false;
            Swal.fire({
                title: 'Gagal!',
                text: Object.values(errors)[0] || 'Terjadi kesalahan saat upload.',
                icon: 'error',
                confirmButtonColor: '#3b82f6'
            });
        }
    });
};
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <div class="py-6 md:py-8">
            <!-- Header dengan Tombol Upload -->
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900">Halo, <span class="text-blue-600">{{ userName }}</span>!</h1>
                    <p class="mt-1 text-sm text-gray-600">Selamat datang di Sistem Informasi E-Arsip.</p>
                </div>
                <!-- Tombol Upload Arsip -->
                <button 
                    @click="showUploadModal = true" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all transform hover:scale-105 active:scale-95"
                >
                    <i class="fa-solid fa-cloud-arrow-up"></i> Tambah Arsip
                </button>
            </div>

            <!-- 1. Statistik Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div><p class="text-sm font-medium text-gray-500">Total Arsip</p><p class="mt-2 text-3xl font-bold text-gray-900">{{ statistik.total }}</p></div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-600"><i class="fa-solid fa-archive text-xl"></i></div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm cursor-pointer hover:border-green-300 transition-colors" @click="selectCategory('gambar')">
                    <div class="flex items-center justify-between">
                        <div><p class="text-sm font-medium text-gray-500">Gambar</p><p class="mt-2 text-3xl font-bold text-gray-900">{{ statistik.gambar }}</p></div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 text-green-600"><i class="fa-solid fa-image text-xl"></i></div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm cursor-pointer hover:border-purple-300 transition-colors" @click="selectCategory('video')">
                    <div class="flex items-center justify-between">
                        <div><p class="text-sm font-medium text-gray-500">Video</p><p class="mt-2 text-3xl font-bold text-gray-900">{{ statistik.video }}</p></div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-purple-600"><i class="fa-solid fa-video text-xl"></i></div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm cursor-pointer hover:border-orange-300 transition-colors" @click="selectCategory('dokumen')">
                    <div class="flex items-center justify-between">
                        <div><p class="text-sm font-medium text-gray-500">Dokumen</p><p class="mt-2 text-3xl font-bold text-gray-900">{{ statistik.dokumen }}</p></div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-100 text-orange-600"><i class="fa-solid fa-file-lines text-xl"></i></div>
                    </div>
                </div>
            </div>

            <!-- 2. DUA GRAFIK UTAMA -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                
                <!-- GRAFIK 1: Bar Chart Harian -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Upload Harian</h3>
                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-md">Klik bar untuk detail</span>
                    </div>
                    <div class="flex items-end justify-between h-48 gap-2 mt-4 px-2">
                        <div v-for="(count, index) in grafik_harian" :key="index" 
                             @click="selectDay(index)"
                             :class="selectedDay === index ? 'bg-blue-600 shadow-lg shadow-blue-500/30' : 'bg-blue-400 hover:bg-blue-500'"
                             class="w-full rounded-t-lg transition-all cursor-pointer relative group"
                             :style="{ height: Math.max(count / maxGrafik * 100, 4) + '%' }">
                             <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                                {{ count }} file
                             </div>
                        </div>
                    </div>
                    <div class="flex justify-between mt-3 px-2 text-xs font-medium text-gray-500">
                        <span v-for="hari in hariList" :key="hari">{{ hari }}</span>
                    </div>
                    <div v-if="selectedDay !== null" class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-100 text-sm text-blue-800 flex items-center gap-2 animate-fade-in">
                        <i class="fa-solid fa-calendar-day text-blue-600"></i>
                        <span>Hari <strong>{{ hariList[selectedDay] }}</strong>: Terdapat <strong>{{ grafik_harian[selectedDay] }}</strong> arsip yang diupload.</span>
                    </div>
                </div>

                <!-- GRAFIK 2: Donut Chart Kategori -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Kategori Arsip</h3>
                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-md">Klik untuk detail</span>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="relative w-40 h-40 flex-shrink-0 mx-auto">
                            <div class="absolute inset-0 rounded-full shadow-inner"
                                 :style="{
                                     background: `conic-gradient(
                                         #22c55e 0% ${persenGambar}%, 
                                         #a855f7 ${persenGambar}% ${persenGambar + persenVideo}%, 
                                         #f97316 ${persenGambar + persenVideo}% 100%
                                     )`
                                 }">
                            </div>
                            <div class="absolute inset-5 bg-white rounded-full flex items-center justify-center flex-col shadow-sm">
                                <span class="text-2xl font-bold text-gray-900">{{ statistik.total }}</span>
                                <span class="text-xs text-gray-500">Total</span>
                            </div>
                        </div>
                        <div class="flex-1 space-y-3">
                            <div @click="selectCategory('gambar')" :class="selectedCategory === 'gambar' ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-100 hover:bg-gray-100'" class="p-3 rounded-xl border cursor-pointer transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-green-500"></div><span class="text-sm font-medium text-gray-700">Gambar</span></div>
                                    <span class="text-sm font-bold text-gray-900">{{ statistik.gambar }}</span>
                                </div>
                            </div>
                            <div @click="selectCategory('video')" :class="selectedCategory === 'video' ? 'bg-purple-50 border-purple-200' : 'bg-gray-50 border-gray-100 hover:bg-gray-100'" class="p-3 rounded-xl border cursor-pointer transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-purple-500"></div><span class="text-sm font-medium text-gray-700">Video</span></div>
                                    <span class="text-sm font-bold text-gray-900">{{ statistik.video }}</span>
                                </div>
                            </div>
                            <div @click="selectCategory('dokumen')" :class="selectedCategory === 'dokumen' ? 'bg-orange-50 border-orange-200' : 'bg-gray-50 border-gray-100 hover:bg-gray-100'" class="p-3 rounded-xl border cursor-pointer transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-orange-500"></div><span class="text-sm font-medium text-gray-700">Dokumen</span></div>
                                    <span class="text-sm font-bold text-gray-900">{{ statistik.dokumen }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="selectedCategory" class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-100 text-sm text-blue-800 flex items-center gap-2 animate-fade-in">
                        <i class="fa-solid fa-folder-open text-blue-600"></i>
                        <span>Kategori <strong class="capitalize">{{ selectedCategory }}</strong>: Terdapat <strong>{{ statistik[selectedCategory] }}</strong> file tersimpan.</span>
                    </div>
                </div>
            </div>

            <!-- 3. Arsip Terbaru -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Arsip Terbaru</h3>
                <div class="space-y-3 max-h-[250px] overflow-y-auto pr-2">
                    <div v-if="arsip.length === 0" class="text-center py-8 text-gray-500">
                        <i class="fa-solid fa-box-open text-3xl mb-2"></i>
                        <p class="text-sm">Belum ada arsip</p>
                    </div>
                    <div v-for="item in arsip.slice(0, 5)" :key="item.id" class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100 hover:border-blue-200 transition-colors">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                            <i class="fa-solid" :class="{ 'fa-image': item.tipe_arsip === 'gambar', 'fa-video': item.tipe_arsip === 'video', 'fa-file-lines': item.tipe_arsip === 'file' }"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ item.nama_arsip }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ item.tipe_arsip }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL UPLOAD ARSIP -->
        <div v-if="showUploadModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" @click.self="showUploadModal = false">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden animate-modal-in">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up text-blue-600"></i> Tambah Arsip Baru
                    </h3>
                    <button @click="showUploadModal = false" class="rounded-lg p-2 text-gray-400 hover:bg-gray-200 hover:text-gray-600 transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <form @submit.prevent="submitUpload" class="p-6 space-y-5">
                    
                    <!-- Nama Arsip -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Arsip</label>
                        <input 
                            v-model="uploadForm.nama_arsip" 
                            type="text" 
                            required 
                            placeholder="Contoh: Laporan Keuangan 2024"
                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-gray-50 focus:bg-white"
                        />
                    </div>

                    <!-- Tipe Arsip -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Arsip</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label :class="uploadForm.tipe_arsip === 'gambar' ? 'bg-green-50 border-green-400 text-green-700' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100'" class="flex flex-col items-center gap-1 p-3 rounded-xl border-2 cursor-pointer transition-all">
                                <input type="radio" v-model="uploadForm.tipe_arsip" value="gambar" class="hidden" />
                                <i class="fa-solid fa-image text-lg"></i>
                                <span class="text-xs font-medium">Gambar</span>
                            </label>
                            <label :class="uploadForm.tipe_arsip === 'video' ? 'bg-purple-50 border-purple-400 text-purple-700' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100'" class="flex flex-col items-center gap-1 p-3 rounded-xl border-2 cursor-pointer transition-all">
                                <input type="radio" v-model="uploadForm.tipe_arsip" value="video" class="hidden" />
                                <i class="fa-solid fa-video text-lg"></i>
                                <span class="text-xs font-medium">Video</span>
                            </label>
                            <label :class="uploadForm.tipe_arsip === 'file' ? 'bg-orange-50 border-orange-400 text-orange-700' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100'" class="flex flex-col items-center gap-1 p-3 rounded-xl border-2 cursor-pointer transition-all">
                                <input type="radio" v-model="uploadForm.tipe_arsip" value="file" class="hidden" />
                                <i class="fa-solid fa-file-alt text-lg"></i>
                                <span class="text-xs font-medium">Dokumen</span>
                            </label>
                        </div>
                    </div>

                    <!-- Upload File -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">File</label>
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-blue-50 hover:border-blue-400 transition-all">
                            <div class="flex flex-col items-center gap-2 text-gray-500">
                                <i class="fa-solid fa-file-arrow-up text-2xl text-blue-500"></i>
                                <span class="text-sm font-medium" v-if="!uploadForm.file">Klik untuk pilih file</span>
                                <span class="text-sm font-medium text-blue-600" v-else>{{ uploadForm.file.name }}</span>
                                <span class="text-xs text-gray-400">Maks. 50MB</span>
                            </div>
                            <input type="file" class="hidden" @change="handleFileChange" />
                        </label>
                    </div>

                    <!-- Tombol Submit -->
                    <button 
                        type="submit" 
                        :disabled="uploading"
                        class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-xl shadow-lg shadow-blue-500/30 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <i class="fa-solid" :class="uploading ? 'fa-spinner fa-spin' : 'fa-cloud-arrow-up'"></i>
                        <span v-if="!uploading">Upload Arsip</span>
                        <span v-else>Mengupload...</span>
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(5px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fade-in 0.3s ease-out forwards; }

@keyframes modal-in {
    from { opacity: 0; transform: scale(0.95) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in { animation: modal-in 0.3s ease-out forwards; }
</style>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3'; 
import { ref } from 'vue';
import VueApexCharts from 'vue3-apexcharts'; // Import library grafik

const props = defineProps({
    statistik: Object,
    arsip: Array,
    grafik_harian: Array,
    user_role: String, // Menangkap role user dari web.php
});

const showModal = ref(false);
const isEditing = ref(false);
const editId = ref(null);

const form = useForm({
    nama_arsip: '',
    tipe_arsip: 'gambar',
    file: null,
});

// === KONFIGURASI GRAFIK ===
// 1. Grafik Garis (Pergerakan Harian)
const lineChartOptions = {
    chart: { type: 'area', toolbar: { show: false }, zoom: { enabled: false } },
    colors: ['#3b82f6'],
    fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [50, 100] } },
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth', width: 3 },
    xaxis: { categories: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] },
};
const lineChartSeries = [{ name: 'Upload Arsip', data: props.grafik_harian }];

// 2. Grafik Batang (Jumlah per Kategori)
const barChartOptions = {
    chart: { type: 'bar', toolbar: { show: false } },
    colors: ['#3b82f6'],
    plotOptions: { bar: { borderRadius: 4, columnWidth: '40%' } },
    dataLabels: { enabled: false },
    xaxis: { categories: ['Gambar', 'Video', 'Dokumen'] },
};
const barChartSeries = [{ name: 'Total File', data: [props.statistik.gambar, props.statistik.video, props.statistik.dokumen] }];
// ==========================

const bukaModalTambah = () => {
    isEditing.value = false;
    editId.value = null;
    form.reset();
    showModal.value = true;
};

const bukaModalEdit = (item) => {
    isEditing.value = true;
    editId.value = item.id;
    form.nama_arsip = item.nama_arsip;
    form.tipe_arsip = item.tipe_arsip;
    form.file = null;
    showModal.value = true;
};

const submitForm = () => {
    if (isEditing.value) {
        form.post(route('arsip.update', editId.value), {
            preserveScroll: true,
            onSuccess: () => { showModal.value = false; form.reset(); alert('Diupdate!'); },
        });
    } else {
        form.post(route('arsip.store'), {
            preserveScroll: true,
            onSuccess: () => { showModal.value = false; form.reset(); alert('Disimpan!'); },
        });
    }
};

const hapusArsip = (id) => {
    if (confirm('Yakin ingin menghapus arsip ini?')) {
        router.delete(route('arsip.destroy', id), { preserveScroll: true });
    }
};

const formatTanggal = (tanggal) => {
    return new Date(tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fa-solid fa-gauge mr-2 text-blue-600"></i> Dashboard Arsip
            </h2>
        </template>

        <!-- Ubah background menjadi putih bersih seperti gambarmu -->
        <div class="py-12 bg-gray-50 min-h-screen relative">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Halo, <span class="text-blue-600">{{ $page.props.auth.user.name }}</span>!</h1>
                        <p class="text-gray-500">Selamat datang di Sistem Informasi E-Arsip.</p>
                    </div>
                    
                    <button @click="bukaModalTambah" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition">
                        <i class="fa-solid fa-plus mr-2"></i> Tambah Arsip
                    </button>
                </div>

                <!-- GRAFIK: HANYA MUNCUL JIKA ROLE == ADMIN -->
                <div v-if="user_role === 'admin'" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Line Chart -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-700 mb-4">Statistik Arsip (Bulan Ini)</h3>
                        <VueApexCharts type="area" height="250" :options="lineChartOptions" :series="lineChartSeries" />
                    </div>
                    <!-- Bar Chart -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-700 mb-4">Jumlah Arsip per Kategori</h3>
                        <VueApexCharts type="bar" height="250" :options="barChartOptions" :series="barChartSeries" />
                    </div>
                </div>

                <!-- CARD STATISTIK (MUNCUL UNTUK SEMUA) -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500 font-semibold mb-1">Total Arsip</p>
                            <h3 class="text-3xl font-bold text-gray-800">{{ statistik.total }}</h3>
                        </div>
                        <div class="bg-blue-50 text-blue-600 p-4 rounded-xl text-2xl"><i class="fa-solid fa-folder-open"></i></div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500 font-semibold mb-1">Gambar</p>
                            <h3 class="text-3xl font-bold text-gray-800">{{ statistik.gambar }}</h3>
                        </div>
                        <div class="bg-green-50 text-green-600 p-4 rounded-xl text-2xl"><i class="fa-solid fa-image"></i></div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500 font-semibold mb-1">Video</p>
                            <h3 class="text-3xl font-bold text-gray-800">{{ statistik.video }}</h3>
                        </div>
                        <div class="bg-purple-50 text-purple-600 p-4 rounded-xl text-2xl"><i class="fa-solid fa-film"></i></div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500 font-semibold mb-1">Dokumen</p>
                            <h3 class="text-3xl font-bold text-gray-800">{{ statistik.dokumen }}</h3>
                        </div>
                        <div class="bg-orange-50 text-orange-600 p-4 rounded-xl text-2xl"><i class="fa-solid fa-file-lines"></i></div>
                    </div>
                </div>

                <!-- TABEL DATA ARSIP -->
                <div class="bg-white overflow-hidden shadow-sm border border-gray-100 rounded-2xl">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800">Daftar Arsip Terbaru</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="text-xs text-gray-400 uppercase bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-4">Nama Arsip</th>
                                    <th class="px-6 py-4">Kategori</th>
                                    <th class="px-6 py-4">Tanggal Upload</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in arsip" :key="item.id" class="border-b hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-medium text-gray-800 whitespace-nowrap">{{ item.nama_arsip }}</td>
                                    <td class="px-6 py-4">
                                        <span v-if="item.tipe_arsip === 'gambar'" class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">Gambar</span>
                                        <span v-else-if="item.tipe_arsip === 'video'" class="bg-purple-100 text-purple-700 text-xs px-3 py-1 rounded-full">Video</span>
                                        <span v-else class="bg-orange-100 text-orange-700 text-xs px-3 py-1 rounded-full">Dokumen</span>
                                    </td>
                                    <td class="px-6 py-4">{{ formatTanggal(item.created_at) }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <a :href="`/storage/${item.path_file}`" target="_blank" class="text-blue-500 hover:text-blue-700 mx-2"><i class="fa-solid fa-eye text-lg"></i></a>
                                        <button @click="bukaModalEdit(item)" class="text-yellow-500 hover:text-yellow-700 mx-2"><i class="fa-solid fa-pen-to-square text-lg"></i></button>
                                        <button @click="hapusArsip(item.id)" class="text-red-500 hover:text-red-700 mx-2"><i class="fa-solid fa-trash text-lg"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!-- MODAL TETAP SAMA -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-40 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-8 relative">
                <button @click="showModal = false" class="absolute top-4 right-5 text-gray-400 hover:text-red-500"><i class="fa-solid fa-xmark text-2xl"></i></button>
                <h3 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-3">
                    <i :class="isEditing ? 'fa-solid fa-pen-to-square' : 'fa-solid fa-cloud-arrow-up'" class="text-blue-600 mr-2"></i> 
                    {{ isEditing ? 'Edit Arsip' : 'Upload Arsip Baru' }}
                </h3>
                <form @submit.prevent="submitForm">
                    <!-- Isi form dipersingkat agar muat -->
                    <div class="mb-4"><label class="block text-sm font-medium text-gray-700 mb-1">Nama Arsip</label><input type="text" v-model="form.nama_arsip" class="w-full border-gray-300 rounded-lg" required></div>
                    <div class="mb-4"><label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label><select v-model="form.tipe_arsip" class="w-full border-gray-300 rounded-lg" required><option value="gambar">Gambar</option><option value="video">Video</option><option value="file">Dokumen</option></select></div>
                    <div class="mb-6"><label class="block text-sm font-medium text-gray-700 mb-1">File</label><input type="file" @input="form.file = $event.target.files[0]" class="w-full border border-gray-300 rounded-lg" :required="!isEditing"></div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="showModal = false" class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg">{{ isEditing ? 'Update' : 'Simpan' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
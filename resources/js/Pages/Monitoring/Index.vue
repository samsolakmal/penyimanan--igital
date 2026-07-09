<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Swal from 'sweetalert2';

const props = defineProps({ statistik: Object, users: Array });
const searchQuery = ref('');

const filteredUsers = computed(() => {
    if (!searchQuery.value) return props.users;
    const query = searchQuery.value.toLowerCase();
    return props.users.filter(user => user.name.toLowerCase().includes(query) || user.email.toLowerCase().includes(query));
});

const deleteUser = (user) => {
    Swal.fire({
        title: 'Hapus Akun?',
        text: `Yakin ingin menghapus akun "${user.name}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#475569',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        background: '#1e293b',
        color: '#f8fafc'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/users/${user.id}`, { preserveScroll: true });
        }
    });
};
</script>

<template>
    <Head title="Monitoring User" />
    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-6">
                    <h1 class="text-3xl font-extrabold text-white">Monitoring Akun</h1>
                    <p class="mt-1 text-sm text-slate-400">Dashboard monitoring dan manajemen akun sistem</p>
                </div>

                <!-- Statistik Cards -->
                <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl shadow-black/20">
                        <p class="text-sm font-medium text-slate-400">Total Akun</p>
                        <p class="mt-2 text-3xl font-bold text-white">{{ statistik.total }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl shadow-black/20">
                        <p class="text-sm font-medium text-slate-400">Super Admin</p>
                        <p class="mt-2 text-3xl font-bold text-red-400">{{ statistik.super_admin }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl shadow-black/20">
                        <p class="text-sm font-medium text-slate-400">Admin</p>
                        <p class="mt-2 text-3xl font-bold text-blue-400">{{ statistik.admin }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl shadow-black/20">
                        <p class="text-sm font-medium text-slate-400">User Biasa</p>
                        <p class="mt-2 text-3xl font-bold text-slate-300">{{ statistik.user }}</p>
                    </div>
                </div>

                <!-- Search -->
                <div class="mb-4">
                    <div class="relative max-w-md">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-slate-500"></i>
                        </div>
                        <input v-model="searchQuery" type="text" placeholder="Cari nama atau email..." class="block w-full rounded-lg border border-slate-700 bg-slate-800 py-2.5 pl-10 pr-3 text-white placeholder-slate-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" />
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl shadow-black/20">
                    <table class="min-w-full divide-y divide-slate-700">
                        <thead class="bg-slate-900/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-400">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-400">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-400">Role</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-slate-400">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700 bg-slate-800">
                            <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-slate-700/50 transition-colors">
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-white">{{ user.name }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-300">{{ user.email }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-300 capitalize">{{ user.role }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                    <button @click="deleteUser(user)" class="inline-flex items-center gap-1 rounded-lg border border-red-500/30 bg-red-500/10 px-3 py-1.5 text-xs font-medium text-red-400 hover:bg-red-500/20 transition-all">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import Swal from 'sweetalert2';

const props = defineProps({
    user: Object,
    currentUserRole: String,
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    role: props.user.role,
});

const submit = () => {
    form.put(route('users.update', props.user.id), {
        onSuccess: () => {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data user berhasil diperbarui.',
                icon: 'success',
                confirmButtonColor: '#2563eb',
                timer: 2000,
                showConfirmButton: false,
            });
        },
        onError: () => {
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat memperbarui data user.',
                icon: 'error',
                confirmButtonColor: '#dc2626',
            });
        },
    });
};

// Role options berdasarkan current user
const roleOptions = computed(() => {
    if (props.currentUserRole === 'super_admin') {
        return [
            { value: 'user', label: 'User' },
            { value: 'admin', label: 'Admin' },
            { value: 'super_admin', label: 'Super Admin' },
        ];
    } else {
        // Admin hanya bisa edit ke role user
        return [
            { value: 'user', label: 'User' },
        ];
    }
});
</script>

<template>
    <Head title="Edit User" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-6">
                    <Link
                        :href="route('users.index')"
                        class="mb-4 inline-flex items-center gap-2 text-sm text-gray-600 transition-colors hover:text-blue-600"
                    >
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke Daftar User
                    </Link>

                    <h1 class="text-3xl font-bold text-gray-900">
                        Edit User
                    </h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Perbarui informasi akun pengguna
                    </p>
                </div>

                <!-- Form Card -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Nama Lengkap
                            </label>
                            <div class="relative mt-1">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    placeholder="Masukkan nama lengkap"
                                    class="block w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-3 text-sm text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                    :class="{ 'border-red-500': form.errors.name }"
                                />
                            </div>
                            <p v-if="form.errors.name" class="mt-1 text-xs text-red-600">
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Email
                            </label>
                            <div class="relative mt-1">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    placeholder="email@example.com"
                                    class="block w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-3 text-sm text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                    :class="{ 'border-red-500': form.errors.email }"
                                />
                            </div>
                            <p v-if="form.errors.email" class="mt-1 text-xs text-red-600">
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <!-- Role Field -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">
                                Role
                            </label>
                            <div class="relative mt-1">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fas fa-user-tag text-gray-400"></i>
                                </div>
                                <select
                                    id="role"
                                    v-model="form.role"
                                    required
                                    class="block w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-10 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                    :class="{ 'border-red-500': form.errors.role }"
                                >
                                    <option
                                        v-for="option in roleOptions"
                                        :key="option.value"
                                        :value="option.value"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select>
                            </div>
                            <p v-if="form.errors.role" class="mt-1 text-xs text-red-600">
                                {{ form.errors.role }}
                            </p>
                            <p v-if="currentUserRole === 'admin'" class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle"></i>
                                Admin hanya dapat mengubah role ke "User"
                            </p>
                        </div>

                        <!-- Info Note -->
                        <div class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                            <div class="flex items-start gap-2 text-sm text-blue-800">
                                <i class="fas fa-info-circle mt-0.5"></i>
                                <div>
                                    <p class="font-medium">Ingin reset password?</p>
                                    <p class="mt-1 text-xs">
                                        Gunakan tombol "Reset" di halaman daftar user untuk mengubah password.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6">
                            <Link
                                :href="route('users.index')"
                                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition-all hover:bg-gray-50"
                            >
                                Batal
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-blue-700 hover:shadow-md disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <i class="fas fa-save"></i>
                                <span v-if="!form.processing">Update User</span>
                                <span v-else>Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
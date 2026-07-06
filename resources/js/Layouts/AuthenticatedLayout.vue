<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const userRole = usePage().props.auth.user.role || 'user';
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex font-sans">
        
        <!-- SIDEBAR KIRI -->
        <aside class="w-64 bg-[#3b82f6] text-white flex-shrink-0 hidden md:flex flex-col shadow-xl z-20">
            <!-- Logo -->
            <div class="h-20 flex items-center justify-center border-b border-blue-500/50">
                <Link href="/dashboard" class="flex items-center gap-3">
                    <div class="bg-white text-blue-600 w-8 h-8 rounded flex items-center justify-center shadow">
                        <i class="fa-solid fa-cube text-lg"></i>
                    </div>
                    <h2 class="text-2xl font-extrabold tracking-wide">E-Arsip</h2>
                </Link>
            </div>
            
            <!-- Menu Navigasi -->
            <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
                <p class="text-blue-200 text-xs font-bold px-4 mb-2 uppercase tracking-wider">Menu Utama</p>
                
                <Link :href="route('dashboard')" class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium" :class="route().current('dashboard') ? 'bg-blue-700/50 shadow-inner' : 'hover:bg-blue-700/50'">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i> Dashboard
                </Link>
                
                <Link :href="route('arsip.index')" class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium" :class="route().current('arsip.*') ? 'bg-blue-700/50 shadow-inner' : 'hover:bg-blue-700/50'">
                    <i class="fa-solid fa-folder-open w-5 text-center"></i> Data Arsip
                </Link>

                <div class="h-4"></div>

                <template v-if="userRole === 'super_admin' || userRole === 'admin'">
                    <p class="text-blue-200 text-xs font-bold px-4 mb-2 uppercase tracking-wider">Laporan & Audit</p>
                    
                    <Link :href="route('log-aktivitas.index')" class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium" :class="route().current('log-aktivitas.*') ? 'bg-blue-700/50 shadow-inner' : 'hover:bg-blue-700/50'">
                        <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i> Log Aktivitas
                    </Link>

                    <div class="h-4"></div>

                    <p class="text-blue-200 text-xs font-bold px-4 mb-2 uppercase tracking-wider">Manajemen</p>
                    
                    <!-- Monitoring User (Khusus Super Admin) -->
                    <Link 
                        v-if="userRole === 'super_admin'"
                        :href="route('monitoring.index')" 
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium"
                        :class="route().current('monitoring.*') ? 'bg-blue-700/50 shadow-inner' : 'hover:bg-blue-700/50'"
                    >
                        <i class="fa-solid fa-chart-line w-5 text-center"></i> Monitoring User
                    </Link>
                    
                    <Link :href="route('users.index')" class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium" :class="route().current('users.*') ? 'bg-blue-700/50 shadow-inner' : 'hover:bg-blue-700/50'">
                        <i class="fa-solid fa-users-gear w-5 text-center"></i> Kelola Pengguna
                    </Link>
                </template>
            </nav>

            <!-- Logout -->
            <div class="p-4 border-t border-blue-500/50">
                <Link :href="route('logout')" method="post" as="button" class="flex items-center gap-3 px-4 py-3 w-full hover:bg-red-500 rounded-xl transition font-medium text-left">
                    <i class="fa-solid fa-right-from-bracket w-5 text-center"></i> Keluar
                </Link>
            </div>
        </aside>
        
        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- TOP NAVBAR -->
            <header class="h-20 bg-white flex items-center justify-between px-8 shadow-sm z-10 relative">
                
                <!-- Hamburger mobile -->
                <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fa-solid fa-bars text-2xl"></i>
                </button>

                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 items-center">
                    <div class="relative w-72">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-3 text-gray-400"></i>
                        <input type="text" placeholder="Cari arsip atau aktivitas..." class="w-full pl-12 pr-4 py-2.5 border border-gray-200 rounded-full focus:ring-blue-500 focus:border-blue-500 bg-gray-50 text-sm shadow-inner transition">
                    </div>
                </div>

                <!-- User Profile -->
                <div class="flex items-center gap-5">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-gray-800">{{ $page.props.auth.user.name }}</p>
                        <p class="text-xs text-gray-500 uppercase font-semibold tracking-wider">
                            <span v-if="userRole === 'super_admin'" class="text-red-500"><i class="fa-solid fa-crown mr-1"></i> Super Admin</span>
                            <span v-else-if="userRole === 'admin'" class="text-blue-500"><i class="fa-solid fa-user-shield mr-1"></i> Admin</span>
                            <span v-else class="text-green-500"><i class="fa-solid fa-user mr-1"></i> User Biasa</span>
                        </p>
                    </div>
                    
                    <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center border-2 border-blue-200 font-bold text-lg">
                        {{ $page.props.auth.user.name.charAt(0) }}
                    </div>
                </div>
            </header>

            <!-- PAGE CONTENT -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#f8fafc]">
                <slot />
            </main>
            
        </div>
    </div>
</template>
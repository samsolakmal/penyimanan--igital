<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

// Konfigurasi Partikel: Hitam Pekat & Jelas
const particlesOptions = {
    background: { color: { value: "#ffffff" } }, 
    particles: {
        color: { value: "#000000" }, 
        // Opacity garis dinaikkan jadi 0.6 dan ditebalkan jadi 1.5
        links: { color: "#000000", distance: 150, enable: true, opacity: 0.6, width: 1.5 },
        move: { enable: true, speed: 1.5 }, // Kecepatan sedikit ditambah biar lebih dinamis
        number: { density: { enable: true, area: 800 }, value: 90 }, // Jumlah partikel ditambah
        opacity: { value: 0.8 }, // Kepekatan titik partikel dinaikkan
        // Ukuran titik partikel diperbesar
        size: { value: { min: 2, max: 4 } },
    },
    interactivity: {
        events: {
            onHover: { enable: true, mode: "grab" }, 
            onClick: { enable: true, mode: "push" }  
        },
        modes: {
            // Saat kursor didekatkan, garis tarikan kursor jadi sangat jelas
            grab: { distance: 160, links: { opacity: 1, width: 2 } },
            push: { quantity: 4 }
        }
    }
};
</script>

<template>
    <Head title="Beranda" />

    <div class="relative min-h-screen bg-white overflow-hidden font-sans text-gray-900">
        
        <vue-particles
            id="tsparticles-welcome"
            :options="particlesOptions"
            class="absolute inset-0 z-0 pointer-events-auto"
        />

        <div class="relative z-10 flex flex-col min-h-screen">
            
            <nav class="flex items-center justify-between px-6 py-6 md:px-16 lg:px-32">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#0f172a] rounded flex items-center justify-center text-white shadow-lg">
                        <i class="fa-solid fa-cube text-xl"></i>
                    </div>
                </div>
                
                <div class="hidden md:flex gap-10 text-sm font-semibold text-gray-600">
                    <a href="#" class="hover:text-blue-600 transition">Beranda</a>
                    <a href="#" class="hover:text-blue-600 transition">Arsip</a>
                    <a href="#" class="hover:text-blue-600 transition">Tentang</a>
                </div>
                
                <div v-if="canLogin" class="flex gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="px-6 py-2.5 text-sm font-bold text-white bg-[#0f172a] rounded-full hover:bg-blue-800 transition shadow-md"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="px-6 py-2.5 text-sm font-bold text-white bg-[#0f172a] rounded-full hover:bg-blue-800 transition shadow-md"
                        >
                            Bantuan / Masuk
                        </Link>
                    </template>
                </div>
            </nav>

            <main class="flex-grow flex flex-col items-center justify-center text-center px-4 -mt-20">
                
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold tracking-tight text-[#0f172a] mb-2 leading-tight">
                    SISTEM <br>
                    INFORMASI E-ARSIP <br>
                    E-ARSIP
                </h1>
                
                <div class="mt-10">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="px-12 py-3.5 text-lg font-bold text-white bg-[#0f172a] rounded-full hover:bg-blue-800 transition shadow-xl hover:-translate-y-1 transform duration-200"
                    >
                        Masuk Dashboard
                    </Link>
                    <Link
                        v-else
                        :href="route('login')"
                        class="px-14 py-3.5 text-lg font-bold text-white bg-[#0f172a] rounded-full hover:bg-blue-800 transition shadow-xl hover:-translate-y-1 transform duration-200"
                    >
                        Masuk
                    </Link>
                </div>

            </main>
        </div>
    </div>
</template>
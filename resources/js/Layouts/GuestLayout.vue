<script setup>
import { Link } from '@inertiajs/vue3';

// Konfigurasi Partikel: Hitam Pekat & Jelas (Sama persis dengan halaman depan)
const particlesOptions = {
    background: { color: { value: "#ffffff" } }, 
    particles: {
        color: { value: "#000000" }, 
        links: { color: "#000000", distance: 150, enable: true, opacity: 0.6, width: 1.5 },
        move: { enable: true, speed: 1.5 },
        number: { density: { enable: true, area: 800 }, value: 90 },
        opacity: { value: 0.8 },
        size: { value: { min: 2, max: 4 } },
    },
    interactivity: {
        events: {
            onHover: { enable: true, mode: "grab" }, 
            onClick: { enable: true, mode: "push" }  
        },
        modes: {
            grab: { distance: 160, links: { opacity: 1, width: 2 } },
            push: { quantity: 4 }
        }
    }
};
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50 relative overflow-hidden font-sans">
        
        <vue-particles
            id="tsparticles-guest"
            :options="particlesOptions"
            class="absolute inset-0 z-0 pointer-events-auto"
        />

        <div class="relative z-10 mb-4 mt-12 sm:mt-0 text-center">
            <Link href="/" class="flex flex-col items-center group">
                <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg mb-4 transform transition group-hover:scale-105">
                    <i class="fa-solid fa-cube text-4xl"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">E-Arsip</h2>
                <p class="text-gray-500 text-sm mt-2 font-medium">Silakan masuk ke akun Anda</p>
            </Link>
        </div>

        <div class="w-full sm:max-w-md mt-2 px-10 py-12 bg-white shadow-2xl sm:rounded-[2rem] relative z-10 border border-gray-100">
            <slot />
        </div>
        
    </div>
</template>

<style>
/* CSS Tambahan agar kotak input bawaan Laravel Breeze jadi lebih elegan dan terang */
input[type="email"],
input[type="password"],
input[type="text"] {
    background-color: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    color: #1f2937 !important;
    border-radius: 0.75rem !important;
    padding: 0.75rem 1rem !important;
    transition: all 0.3s ease;
}

input[type="email"]:focus,
input[type="password"]:focus,
input[type="text"]:focus {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
}

/* Mempercantik tombol login bawaan Breeze */
button[type="submit"] {
    background-color: #3b82f6 !important;
    border-radius: 9999px !important; /* Bentuk pill/melingkar */
    padding-top: 0.75rem !important;
    padding-bottom: 0.75rem !important;
    width: 100% !important;
    display: flex !important;
    justify-content: center !important;
    font-size: 1rem !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.05em !important;
    transition: all 0.3s ease !important;
}

button[type="submit"]:hover {
    background-color: #1d4ed8 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3) !important;
}

/* Menyesuaikan warna teks label agar tidak terlalu gelap */
label {
    color: #4b5563 !important;
    font-weight: 600 !important;
}
</style>
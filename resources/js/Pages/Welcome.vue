<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

defineProps({ canLogin: Boolean, canRegister: Boolean, laravelVersion: String, phpVersion: String });

const particles = ref([]);
const animationPhase = ref('forming');

const folderMatrix = [
    [1,1,1,1,0,0,0,0,0,0], [1,1,1,1,1,1,1,1,1,1],
    [1,1,1,1,1,1,1,1,1,1], [1,1,1,1,1,1,1,1,1,1],
    [1,1,1,1,1,1,1,1,1,1], [1,1,1,1,1,1,1,1,1,1],
];

onMounted(() => {
    const spacing = 14;
    const startX = -(folderMatrix[0].length * spacing) / 2;
    const startY = -(folderMatrix.length * spacing) / 2;
    let id = 0;

    folderMatrix.forEach((row, rIndex) => {
        row.forEach((val, cIndex) => {
            if (val === 1) {
                const randomAngle = Math.random() * Math.PI * 2;
                const randomDist = 250 + Math.random() * 200;
                const startXPos = Math.cos(randomAngle) * randomDist;
                const startYPos = Math.sin(randomAngle) * randomDist;
                const targetX = startX + (cIndex * spacing);
                const targetY = startY + (rIndex * spacing);

                particles.value.push({
                    id: id++, startX: startXPos, startY: startYPos,
                    endX: targetX, endY: targetY,
                    delay: Math.random() * 0.8, duration: 1.5 + Math.random() * 1
                });
            }
        });
    });
    setTimeout(() => { animationPhase.value = 'formed'; }, 2500);
});
</script>

<template>
    <Head title="Selamat Datang - E-Arsip" />
    <div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

        <div class="relative h-40 w-40 flex items-center justify-center mb-8">
            <div v-for="p in particles" :key="p.id" class="absolute w-2.5 h-2.5 rounded-full bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.6)]"
                :style="{ '--sx': p.startX + 'px', '--sy': p.startY + 'px', '--ex': p.endX + 'px', '--ey': p.endY + 'px', animationDelay: p.delay + 's', animationDuration: p.duration + 's' }"
                :class="animationPhase === 'forming' ? 'animate-form' : 'animate-pulse-shape'"></div>
        </div>

        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto" :class="animationPhase === 'formed' ? 'animate-fade-in-up' : 'opacity-0'">
            <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 tracking-tight mb-4">E-<span class="text-blue-600">Arsip</span></h1>
            <p class="text-lg md:text-xl text-gray-600 mb-10 max-w-2xl mx-auto leading-relaxed">Sistem Informasi Penyimpanan Digital yang aman, modern, dan mudah digunakan.</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <template v-if="canLogin">
                    <Link :href="route('login')" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all transform hover:scale-105">
                        <i class="fa-solid fa-right-to-bracket"></i> Masuk
                    </Link>
                    <Link v-if="canRegister" :href="route('register')" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-blue-600 font-bold rounded-xl border-2 border-blue-100 shadow-md hover:bg-blue-50 transition-all transform hover:scale-105">
                        <i class="fa-solid fa-user-plus"></i> Daftar Akun
                    </Link>
                </template>
            </div>
            <div class="mt-16 text-sm text-gray-400"><p>Dibuat dengan Laravel {{ laravelVersion }} (PHP {{ phpVersion }}) & Vue 3</p></div>
        </div>
    </div>
</template>

<style scoped>
@keyframes blob { 0% { transform: translate(0px, 0px) scale(1); } 33% { transform: translate(30px, -50px) scale(1.1); } 66% { transform: translate(-20px, 20px) scale(0.9); } 100% { transform: translate(0px, 0px) scale(1); } }
.animate-blob { animation: blob 7s infinite; }
.animation-delay-2000 { animation-delay: 2s; }
@keyframes form { 0% { transform: translate(var(--sx), var(--sy)) scale(0); opacity: 0; } 100% { transform: translate(var(--ex), var(--ey)) scale(1); opacity: 1; } }
.animate-form { animation-name: form; animation-timing-function: cubic-bezier(0.2, 0.8, 0.2, 1); animation-fill-mode: forwards; }
@keyframes pulse-shape { 0%, 100% { opacity: 0.8; transform: translate(var(--ex), var(--ey)) scale(1); } 50% { opacity: 1; transform: translate(var(--ex), var(--ey)) scale(1.3); box-shadow: 0 0 15px rgba(59,130,246,0.8); } }
.animate-pulse-shape { animation: pulse-shape 2s infinite ease-in-out; animation-delay: 2s; }
@keyframes fade-in-up { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
.animate-fade-in-up { animation: fade-in-up 1s ease-out forwards; }
</style>
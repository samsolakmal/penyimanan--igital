<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

defineProps({ canResetPassword: Boolean });

const form = useForm({ email: '', password: '', remember: false });
const submit = () => { form.post(route('login'), { onFinish: () => form.reset('password') }); };

const particles = ref([]);
const animationPhase = ref('forming');

const lockMatrix = [
    [0,0,0,1,1,1,1,0,0,0], [0,0,1,1,0,0,1,1,0,0],
    [0,0,1,1,0,0,1,1,0,0], [0,0,1,1,0,0,1,1,0,0],
    [0,1,1,1,1,1,1,1,1,0], [0,1,1,1,1,1,1,1,1,0],
    [0,1,1,1,1,1,1,1,1,0], [0,1,1,1,1,1,1,1,1,0],
];

onMounted(() => {
    const spacing = 12;
    const startX = -(lockMatrix[0].length * spacing) / 2;
    const startY = -(lockMatrix.length * spacing) / 2;
    let id = 0;

    lockMatrix.forEach((row, rIndex) => {
        row.forEach((val, cIndex) => {
            if (val === 1) {
                const randomAngle = Math.random() * Math.PI * 2;
                const randomDist = 200 + Math.random() * 150;
                const startXPos = Math.cos(randomAngle) * randomDist;
                const startYPos = Math.sin(randomAngle) * randomDist;
                const targetX = startX + (cIndex * spacing);
                const targetY = startY + (rIndex * spacing);

                particles.value.push({
                    id: id++, startX: startXPos, startY: startYPos,
                    endX: targetX, endY: targetY,
                    delay: Math.random() * 0.3, duration: 0.8 + Math.random() * 0.5
                });
            }
        });
    });
    setTimeout(() => { animationPhase.value = 'formed'; }, 1200);
});
</script>

<template>
    <Head title="Masuk - E-Arsip" />
    <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

        <div class="max-w-md w-full space-y-8 relative z-10">
            <div class="flex flex-col items-center">
                <div class="relative h-32 w-32 flex items-center justify-center mb-6">
                    <div v-for="p in particles" :key="p.id" class="absolute w-2 h-2 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.6)]"
                        :style="{ '--sx': p.startX + 'px', '--sy': p.startY + 'px', '--ex': p.endX + 'px', '--ey': p.endY + 'px', animationDelay: p.delay + 's', animationDuration: p.duration + 's' }"
                        :class="animationPhase === 'forming' ? 'animate-form' : 'animate-pulse-shape'"></div>
                </div>
                <h2 class="text-3xl font-extrabold tracking-tight mb-2 text-gray-900" :class="animationPhase === 'formed' ? 'animate-fade-in-up' : 'opacity-0'">Masuk ke Akun</h2>
                <p class="text-gray-600 text-sm" :class="animationPhase === 'formed' ? 'animate-fade-in-up delay-100' : 'opacity-0'">Masukkan email dan password Anda</p>
            </div>

            <form class="mt-8 space-y-6 bg-white p-8 rounded-2xl border border-gray-100 shadow-xl" :class="animationPhase === 'formed' ? 'animate-fade-in-up delay-200' : 'opacity-0'" @submit.prevent="submit">
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fa-solid fa-envelope text-gray-400"></i></div>
                        <input id="email" v-model="form.email" type="email" required autofocus autocomplete="username" placeholder="nama@email.com" class="block w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-gray-50 focus:bg-white" />
                    </div>
                    <p v-if="form.errors.email" class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ form.errors.email }}</p>
                </div>
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fa-solid fa-lock text-gray-400"></i></div>
                        <input id="password" v-model="form.password" type="password" required autocomplete="current-password" placeholder="••••••••" class="block w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-gray-50 focus:bg-white" />
                    </div>
                    <p v-if="form.errors.password" class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ form.errors.password }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" v-model="form.remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                        <span class="ml-2 block text-sm text-gray-700">Ingat saya</span>
                    </label>
                    <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm font-medium text-blue-600 hover:text-blue-500 transition-colors">Lupa password?</Link>
                </div>
                <button type="submit" :disabled="form.processing" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-500/30 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-right-to-bracket"></i> <span v-if="!form.processing">Masuk</span><span v-else>Memproses...</span>
                </button>
            </form>
            <div class="text-center mt-6" :class="animationPhase === 'formed' ? 'animate-fade-in-up delay-300' : 'opacity-0'">
                <p class="text-sm text-gray-500">Belum punya akun? <Link :href="route('register')" class="font-semibold text-blue-600 hover:text-blue-500 transition-colors">Daftar sekarang</Link></p>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes blob { 0% { transform: translate(0px, 0px) scale(1); } 33% { transform: translate(30px, -50px) scale(1.1); } 66% { transform: translate(-20px, 20px) scale(0.9); } 100% { transform: translate(0px, 0px) scale(1); } }
.animate-blob { animation: blob 7s infinite; }
.animation-delay-2000 { animation-delay: 2s; }
@keyframes form { 0% { transform: translate(var(--sx), var(--sy)) scale(0); opacity: 0; } 100% { transform: translate(var(--ex), var(--ey)) scale(1); opacity: 1; } }
.animate-form { animation-name: form; animation-timing-function: cubic-bezier(0.2, 0.8, 0.2, 1); animation-fill-mode: forwards; }
@keyframes pulse-shape { 0%, 100% { opacity: 0.8; transform: translate(var(--ex), var(--ey)) scale(1); } 50% { opacity: 1; transform: translate(var(--ex), var(--ey)) scale(1.2); box-shadow: 0 0 12px rgba(59,130,246,0.6); } }
.animate-pulse-shape { animation: pulse-shape 2s infinite ease-in-out; animation-delay: 1s; }
@keyframes fade-in-up { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
.animate-fade-in-up { animation: fade-in-up 0.6s ease-out forwards; }
.delay-100 { animation-delay: 0.1s; opacity: 0; }
.delay-200 { animation-delay: 0.2s; opacity: 0; }
.delay-300 { animation-delay: 0.3s; opacity: 0; }
</style>
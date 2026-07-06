<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\UserController;
use App\Models\Archive; 
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Carbon\Carbon;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $currentUser = auth()->user();
    $baseQuery = Archive::query();
    
    if ($currentUser->role === 'user') {
        $baseQuery->where('user_id', $currentUser->id);
    }

    $statistik = [
        'total' => (clone $baseQuery)->count(),
        'gambar' => (clone $baseQuery)->where('tipe_arsip', 'gambar')->count(),
        'video' => (clone $baseQuery)->where('tipe_arsip', 'video')->count(),
        'dokumen' => (clone $baseQuery)->where('tipe_arsip', 'file')->count(),
    ];

    $grafik_harian = [];
    for ($i = 0; $i < 7; $i++) {
        $tanggalHariIni = Carbon::now()->startOfWeek()->addDays($i);
        $hitungFile = (clone $baseQuery)->whereDate('created_at', $tanggalHariIni)->count();
        $grafik_harian[] = $hitungFile;
    }

    return Inertia::render('Dashboard', [
        'statistik' => $statistik,
        'arsip' => $baseQuery->latest()->get(),
        'grafik_harian' => $grafik_harian, 
        'user_role' => $currentUser->role ?? 'user', 
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Route Data Arsip
    Route::get('/arsip', [ArchiveController::class, 'index'])->name('arsip.index');
    Route::post('/arsip', [ArchiveController::class, 'store'])->name('arsip.store');
    Route::delete('/arsip/{id}', [ArchiveController::class, 'destroy'])->name('arsip.destroy');
    Route::post('/arsip/{id}', [ArchiveController::class, 'update'])->name('arsip.update');

    // Route Log Aktivitas
    Route::get('/log-aktivitas', function () {
        return Inertia::render('LogAktivitas/Index');
    })->name('log-aktivitas.index');

    // Route Monitoring User (Khusus Super Admin)
    Route::get('/monitoring-user', [UserController::class, 'monitoring'])->name('monitoring.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('users', UserController::class);
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
});

require __DIR__.'/auth.php';
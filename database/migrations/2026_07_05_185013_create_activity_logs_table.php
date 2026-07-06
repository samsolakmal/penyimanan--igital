<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            // Menghubungkan log dengan user yang login
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Kolom untuk jenis aksi (Upload, Edit, Hapus)
            $table->string('action'); 
            
            // Kolom untuk detail aktivitas
            $table->string('description'); 
            
            // Kolom untuk melacak alamat IP (fitur keamanan pro)
            $table->string('ip_address')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
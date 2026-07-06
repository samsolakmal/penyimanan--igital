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
    Schema::create('archives', function (Blueprint $table) {
        $table->id();
        // Menghubungkan file dengan user yang upload
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('nama_arsip');
        $table->string('path_file'); // Tempat menyimpan file di storage
        // Kategori: gambar, video, file
        $table->enum('tipe_arsip', ['gambar', 'video', 'file']); 
        $table->string('ukuran_file')->nullable();
        $table->timestamps(); // Ini otomatis mencatat bulan dan tahun upload
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archives');
    }
};

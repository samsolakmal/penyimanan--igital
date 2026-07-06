<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    // Tambahkan baris ini bro untuk memberi izin mass-assignment
    protected $fillable = [
        'user_id',
        'nama_arsip',
        'tipe_arsip',
        'path_file',
        'ukuran_file',
    ];
}
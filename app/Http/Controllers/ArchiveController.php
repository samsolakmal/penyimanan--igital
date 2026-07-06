<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ArchiveController extends Controller
{
    /**
     * Tampilkan halaman daftar arsip.
     * User biasa hanya melihat arsip miliknya sendiri.
     * Admin/Super Admin melihat semua arsip.
     */
    public function index()
    {
        $currentUser = auth()->user();
        $query = Archive::query();

        // PENTING: Filter berdasarkan role
        if ($currentUser->role === 'user') {
            $query->where('user_id', $currentUser->id);
        }

        $arsip = $query->latest()->get();
        
        return Inertia::render('Arsip/Index', [
            'arsip' => $arsip,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_arsip' => 'required|string|max:255',
            'tipe_arsip' => 'required|in:gambar,video,file',
            'file' => 'required|file|max:51200',
        ]);

        $file = $request->file('file');
        $path = $file->store('archives', 'public');

        Archive::create([
            'user_id' => auth()->id(),
            'nama_arsip' => $request->nama_arsip,
            'tipe_arsip' => $request->tipe_arsip,
            'path_file' => $path,
            'ukuran_file' => $file->getSize(),
        ]);

        return back();
    }

    public function destroy($id)
    {
        $arsip = Archive::findOrFail($id);

        // Keamanan tambahan: User biasa tidak boleh hapus arsip orang lain
        if (auth()->user()->role === 'user' && $arsip->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if (Storage::disk('public')->exists($arsip->path_file)) {
            Storage::disk('public')->delete($arsip->path_file);
        }

        $arsip->delete();

        return back();
    }

    public function update(Request $request, $id)
    {
        $arsip = Archive::findOrFail($id);

        // Keamanan tambahan: User biasa tidak boleh edit arsip orang lain
        if (auth()->user()->role === 'user' && $arsip->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'nama_arsip' => 'required|string|max:255',
            'tipe_arsip' => 'required|in:gambar,video,file',
            'file' => 'nullable|file|max:51200',
        ]);

        $arsip->nama_arsip = $request->nama_arsip;
        $arsip->tipe_arsip = $request->tipe_arsip;

        if ($request->hasFile('file')) {
            if (Storage::disk('public')->exists($arsip->path_file)) {
                Storage::disk('public')->delete($arsip->path_file);
            }
            $file = $request->file('file');
            $arsip->path_file = $file->store('archives', 'public');
            $arsip->ukuran_file = $file->getSize();
        }

        $arsip->save();

        return back();
    }
}
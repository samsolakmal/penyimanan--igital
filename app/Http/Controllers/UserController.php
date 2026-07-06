<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);
        $currentUser = auth()->user();

        if ($currentUser->role === 'admin') {
            $users = User::where('role', 'user')->get();
        } else {
            $users = User::all();
        }

        return Inertia::render('Users/ManageUsers', [
            'users' => $users->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'role' => $u->role,
                    'created_at' => $u->created_at->format('d M Y'),
                ];
            }),
            'currentUserRole' => $currentUser->role,
        ]);
    }

    public function create()
    {
        $this->authorize('create', User::class);
        return Inertia::render('Users/CreateUser', [
            'currentUserRole' => auth()->user()->role,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:user,admin,super_admin'],
        ]);

        $currentUser = auth()->user();
        if ($currentUser->role === 'admin' && $validated['role'] !== 'user') {
            return back()->withErrors(['role' => 'Admin hanya bisa membuat user biasa.']);
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return Inertia::render('Users/EditUser', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'currentUserRole' => auth()->user()->role,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:user,admin,super_admin'],
        ]);

        $currentUser = auth()->user();
        if ($currentUser->role === 'admin' && $validated['role'] !== 'user') {
            return back()->withErrors(['role' => 'Admin hanya bisa mengubah ke role user.']);
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $this->authorize('resetPassword', $user);

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('users.index')->with('success', 'Password berhasil direset.');
    }

    /**
     * Dashboard monitoring user (khusus Super Admin)
     */
    public function monitoring()
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized access.');
        }

        $statistik = [
            'total' => User::count(),
            'super_admin' => User::where('role', 'super_admin')->count(),
            'admin' => User::where('role', 'admin')->count(),
            'user' => User::where('role', 'user')->count(),
        ];

        $users = User::latest()->get()->map(function ($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'role' => $u->role,
                'created_at' => $u->created_at->format('d M Y'),
            ];
        });

        return Inertia::render('Monitoring/Index', [
            'statistik' => $statistik,
            'users' => $users,
        ]);
    }
}
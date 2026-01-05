<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // MENAMPILKAN DAFTAR USER
    public function index(Request $request)
    {
        // 1. Ambil Query Dasar + Eager Load
        $query = User::with('roles'); 

        // 2. Logic FILTER ROLE (Baru)
        if ($request->filled('role')) {
            // Ini fitur bawaan Spatie untuk filter berdasarkan nama role
            $query->role($request->role);
            
            // CATATAN: Jika kode di atas error, ganti dengan cara manual ini:
            // $query->whereHas('roles', function($q) use ($request) {
            //     $q->where('name', $request->role);
            // });
        }

        // 3. Logic SEARCH (Pencarian)
        if ($request->filled('search')) {
            $keyword = $request->search;
            
            // PENTING: Kita bungkus dalam closure function ($q)
            // Agar logika "OR" tidak merusak filter Role di atas.
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                ->orWhere('email', 'LIKE', "%{$keyword}%");
            });
        }

        // 4. Pagination (10 baris)
        // withQueryString() berguna agar saat klik Page 2, filter search/role tetap terbawa
        $users = $query->latest()
                    ->paginate(10)
                    ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // HALAMAN TAMBAH USER
    public function create()
    {
        $roles = Role::all(); // Kirim data role untuk dropdown
        return view('admin.users.create', compact('roles'));
    }

    // SIMPAN USER BARU
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role); // Beri role

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    // HALAMAN EDIT USER
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    // UPDATE USER
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Password hanya diupdate jika diisi
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles($request->role); // Update role

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui');
    }

    // HAPUS USER (DENGAN PENGAMAN)
    public function destroy(User $user)
    {
        // 1. PENGAMAN: Cek apakah user yang mau dihapus adalah diri sendiri?
        if ($user->id == auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun yang sedang digunakan!');
        }

        // 2. PENGAMAN TAMBAHAN: Jangan biarkan Admin menghapus "Super Admin" (ID 1)
        // (Ini opsional, jaga-jaga kalau kamu punya satu akun keramat)
        if ($user->id == 1) {
             return redirect()->back()->with('error', 'Akun Utama (Super Admin) tidak boleh dihapus!');
        }

        // Kalau lolos pengecekan, baru hapus
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }
}
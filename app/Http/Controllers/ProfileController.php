<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage; // WAJIB ADA: Untuk hapus/simpan foto
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan formulir profil user.
     */
    public function edit(Request $request): View
    {
        return view('user.profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Memperbarui informasi profil user (Nama, Email, Alamat, Foto).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // 1. Ambil data user yang sedang login
        $user = $request->user();

        // 2. Isi data dasar (Nama & Email) dari validasi
        $user->fill($request->validated());

        // 3. Cek apakah Email berubah? (Jika berubah, reset status verifikasi)
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // 4. Update Alamat (Address)
        // Kita ambil input 'address' secara manual jika ada
        if ($request->has('address')) {
            $user->address = $request->input('address');
        }

        // 5. LOGIKA UPLOAD FOTO (AVATAR)
        if ($request->hasFile('avatar')) {
            
            // Validasi ulang khusus gambar (Double check biar aman)
            $request->validate([
                'avatar' => 'image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            ]);

            // Hapus foto lama jika ada (supaya server tidak penuh sampah file lama)
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan foto baru ke folder 'avatars'
            // Hasilnya akan seperti: avatars/namafileacak.jpg
            $path = $request->file('avatar')->store('avatars', 'public');
            
            // Simpan alamat filenya ke database
            $user->avatar = $path;
        }

        // 6. Simpan Semua Perubahan ke Database
        $user->save();

        // 7. Kembali ke halaman edit dengan pesan sukses
        return Redirect::route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Menghapus akun user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
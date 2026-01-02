<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourtController extends Controller
{
    // 1. TAMPILKAN SEMUA DATA
    public function index()
    {
        $courts = Court::all();
        return view('admin.courts.index', compact('courts'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        return view('admin.courts.create');
    }

    // 3. SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'price_per_hour' => 'required|numeric',
            'status' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Upload Gambar
        $imagePath = $request->file('photo')->store('court_images', 'public');

        Court::create([
            'name' => $request->name,
            'type' => $request->type,
            'price_per_hour' => $request->price_per_hour,
            'status' => $request->status,
            'photo' => $imagePath,
        ]);

        return redirect()->route('admin.courts.index')->with('success', 'Lapangan berhasil ditambahkan!');
    }

    // 4. FORM EDIT
    public function edit($id)
    {
        $court = Court::findOrFail($id);
        return view('admin.courts.edit', compact('court'));
    }

    // 5. UPDATE DATA
    public function update(Request $request, $id)
    {
        $court = Court::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'price_per_hour' => 'required|numeric',
            'status' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'type' => $request->type,
            'price_per_hour' => $request->price_per_hour,
            'status' => $request->status,
        ];

        // LOGIKA GANTI FOTO (PENTING)
        // Jika user upload foto baru:
        if ($request->hasFile('photo')) {
            // 1. Hapus foto lama dari penyimpanan (biar server gak penuh sampah)
            if ($court->photo) {
                Storage::disk('public')->delete($court->photo);
            }
            // 2. Simpan foto baru
            $data['photo'] = $request->file('photo')->store('court_images', 'public');
        }
        // Jika user TIDAK upload foto, variabel $data['photo'] tidak diisi, 
        // jadi di database tetap pakai foto yang lama.

        $court->update($data);

        return redirect()->route('admin.courts.index')->with('success', 'Data lapangan berhasil diperbarui!');
    }

    // 6. HAPUS DATA
    public function destroy($id)
    {
        $court = Court::findOrFail($id);
        
        if ($court->photo) {
            Storage::disk('public')->delete($court->photo);
        }

        $court->delete();

        return redirect()->route('admin.courts.index')->with('success', 'Lapangan berhasil dihapus!');
    }
}
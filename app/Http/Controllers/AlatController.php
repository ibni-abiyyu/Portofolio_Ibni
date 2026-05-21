<?php
// app/Http/Controllers/AlatController.php

namespace App\Http\Controllers;

use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlatController extends Controller
{
    public function index()
    {
        $alats = Alat::latest()->paginate(10);
        return view('alat.index', compact('alats'));
    }

    public function create()
    {
        return view('alat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('alat-images', 'public');
            $validated['image'] = $imagePath;
        }

        Alat::create($validated);

        return redirect()->route('alat.index')
            ->with('success', 'Produk alat tulis berhasil ditambahkan.');
    }

    public function show(Alat $alat)
    {
        return view('alat.show', compact('alat'));
    }

    public function edit(Alat $alat)
    {
        return view('alat.edit', compact('alat'));
    }

    public function update(Request $request, Alat $alat)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($alat->image) {
                Storage::disk('public')->delete($alat->image);
            }
            
            $imagePath = $request->file('image')->store('alat-images', 'public');
            $validated['image'] = $imagePath;
        }

        $alat->update($validated);

        return redirect()->route('alat.index')
            ->with('success', 'Produk alat tulis berhasil diperbarui.');
    }

    public function destroy(Alat $alat)
    {
        // Hapus gambar jika ada
        if ($alat->image) {
            Storage::disk('public')->delete($alat->image);
        }

        $alat->delete();

        return redirect()->route('alat.index')
            ->with('success', 'Produk alat tulis berhasil dihapus.');
    }
}
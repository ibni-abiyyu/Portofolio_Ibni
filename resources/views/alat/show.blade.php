@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Detail Produk Alat Tulis</h2>
                    <a href="{{ route('alat.index') }}" class="bg-gray-600 hover:bg-gray-800 text-gray-900 font-bold py-2 px-4 rounded shadow">
                        ← Kembali
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        @if($alat->image)
                            <img src="{{ asset('storage/' . $alat->image) }}" alt="{{ $alat->name }}" class="w-full rounded-lg shadow-lg">
                        @else
                            <div class="bg-gray-100 rounded-lg p-12 text-center text-gray-400 border-2 border-dashed">
                                Tidak ada gambar
                            </div>
                        @endif
                    </div>

                    <div>
                        <table class="min-w-full">
                            <tr>
                                <td class="py-2 font-bold w-32">Nama</td>
                                <td class="py-2">: {{ $alat->name }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 font-bold">Harga</td>
                                <td class="py-2">: Rp {{ number_format($alat->price, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 font-bold">Stok</td>
                                <td class="py-2">: {{ $alat->stock }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 font-bold">Deskripsi</td>
                                <td class="py-2">: {{ $alat->description ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 font-bold">Dibuat</td>
                                <td class="py-2">: {{ $alat->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="py-2 font-bold">Diupdate</td>
                                <td class="py-2">: {{ $alat->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>

                        <div class="mt-6 flex gap-2">
                            <a href="{{ route('alat.edit', $alat) }}" class="bg-yellow-600 hover:bg-yellow-800 text-gray-900 font-bold py-2 px-4 rounded shadow">
                                Edit
                            </a>
                            <form action="{{ route('alat.destroy', $alat) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-800 text-gray-900 font-bold py-2 px-4 rounded shadow" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
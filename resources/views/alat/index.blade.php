@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Daftar Alat Tulis</h2>
                    <a href="{{ route('alat.create') }}" class="bg-blue-600 hover:bg-blue-800 text-gray-900 font-bold py-2 px-4 rounded shadow-lg transition duration-300">
                        + Tambah Produk
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($alats as $index => $alat)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($alat->image)
                                        <img src="{{ asset('storage/' . $alat->image) }}" alt="{{ $alat->name }}" class="w-16 h-16 object-cover rounded">
                                    @else
                                        <span class="text-gray-400">Tidak ada gambar</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $alat->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($alat->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $alat->stock }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('alat.show', $alat) }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-gray-900 font-bold py-1 px-3 rounded text-sm mr-1">Detail</a>
                                    <a href="{{ route('alat.edit', $alat) }}" class="inline-block bg-yellow-500 hover:bg-yellow-700 text-gray-900 font-bold py-1 px-3 rounded text-sm mr-1">Edit</a>
                                    <form action="{{ route('alat.destroy', $alat) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-gray-900 font-bold py-1 px-3 rounded text-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Belum ada data alat tulis.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $alats->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
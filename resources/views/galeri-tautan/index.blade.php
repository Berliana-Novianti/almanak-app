<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Galeri Tautan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h3 class="font-bold text-lg mb-4">Pilih Tahun</h3>
                <form action="{{ route('galeri-tautan.index') }}" method="GET" class="flex items-end space-x-4">
                    <div>
                        <label for="year" class="block font-medium text-sm text-gray-700">Tahun:</label>
                        <select name="year" id="year" class="border-gray-300 rounded-md shadow-sm mt-1">
                            @php
                                $startYear = 2023; // Tahun paling awal yang bisa dipilih
                                $endYear = 2030;   // Tahun paling akhir sesuai permintaan
                            @endphp
                            @for ($y = $endYear; $y >= $startYear; $y--)
                                <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Tampilkan</button>
                </form>
            </div>

            @if(Auth::user()->isSuperAdmin())
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h3 class="font-bold text-lg mb-4">Tambah Tautan Baru</h3>
                <form action="{{ route('galeri-tautan.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="year" value="{{ $selectedYear }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-1">
                            <label for="title" class="block font-medium text-sm text-gray-700">Judul Tautan</label>
                            <input type="text" name="title" id="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div class="md:col-span-2">
                            <label for="url" class="block font-medium text-sm text-gray-700">URL (Link Google Drive)</label>
                            <input type="url" name="url" id="url" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required placeholder="https://">
                        </div>
                    </div>
                    <button type="submit" class="mt-4 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Simpan Tautan</button>
                </form>
            </div>
            @endif

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-bold text-lg mb-4">Daftar Tautan - Tahun {{ $selectedYear }}</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @forelse ($links as $link)
                        <div class="relative group">
                            <a href="{{ $link->url }}" target="_blank" class="flex flex-col items-center justify-center p-4 border rounded-lg hover:bg-gray-100 hover:shadow-md transition aspect-square">
                                <svg class="w-16 h-16 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                                <p class="text-xs text-center mt-2 font-semibold break-all">{{ Str::limit($link->title, 30) }}</p>
                            </a>
                            @if(Auth::user()->isSuperAdmin())
                                <form action="{{ route('galeri-tautan.destroy', $link) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus tautan ini?');" class="absolute top-0 right-0 m-1 opacity-0 group-hover:opacity-100">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 bg-red-500 text-white rounded-full hover:bg-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500 col-span-full">Tidak ada tautan yang ditambahkan untuk tahun ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
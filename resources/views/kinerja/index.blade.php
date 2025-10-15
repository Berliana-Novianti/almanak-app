<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Realisasi Kegiatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-md shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-md shadow-sm">
                    <div class="flex">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <strong class="font-semibold">Terjadi kesalahan:</strong>
                            <ul class="mt-2 list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error) 
                                    <li>{{ $error }}</li> 
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Section Unduh Laporan --}}
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-200">
                <div class="flex items-center mb-4">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="font-bold text-lg text-gray-800">Unduh Laporan Bulanan</h3>
                </div>
                
                <form action="{{ route('kinerja.export') }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[150px]">
                        <label for="year" class="block font-medium text-sm text-gray-700 mb-1">Tahun</label>
                        <select name="year" id="year" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @for ($y = now()->year; $y >= 2023; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="flex-1 min-w-[150px]">
                        <label for="month" class="block font-medium text-sm text-gray-700 mb-1">Bulan</label>
                        <select name="month" id="month" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" class="px-6 py-2.5 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200 flex items-center shadow-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Unduh Excel
                    </button>
                </form>
            </div>

            {{-- Navigasi Bulan --}}
            <div class="flex justify-between items-center bg-white p-5 rounded-lg shadow-sm mb-6 border border-gray-200">
                <a href="{{ route('kinerja.index', ['bulan' => $currentDate->copy()->subMonth()->format('Y-m')]) }}" 
                   class="px-4 py-2 bg-[#1A7EFB] text-white rounded-md hover:bg-blue-700 transition-colors duration-200 shadow-sm font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                
                <h3 class="text-xl font-bold text-gray-800">
                    {{ $currentDate->translatedFormat('F Y') }}
                </h3>
                
                <a href="{{ route('kinerja.index', ['bulan' => $currentDate->copy()->addMonth()->format('Y-m')]) }}" 
                   class="px-4 py-2 bg-[#1A7EFB] text-white rounded-md hover:bg-blue-700 transition-colors duration-200 shadow-sm font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- Tombol Tambah Kegiatan --}}
            <div class="mb-6">
                <button onclick="openModal()" class="inline-flex items-center px-6 py-3 bg-[#1A7EFB] text-white rounded-md hover:bg-blue-700 transition-colors duration-200 shadow-sm font-medium">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Kegiatan Baru
                </button>
            </div>

            {{-- Grid Kartu Kinerja --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse ($kinerjaBulanan as $kinerja)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col hover:shadow-md transition-shadow duration-200">
                        
                        {{-- Header Kartu --}}
                        <div class="p-5 bg-gradient-to-r from-[#BBD8FF] to-[#D4E4FF]">
                            <h4 class="text-center font-bold text-lg text-gray-800">
                                {{ $kinerja->judul_kegiatan }}
                            </h4>
                        </div>
                        
                        {{-- Konten Kartu --}}
                        <div class="p-6 flex-grow space-y-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">Target</p>
                                <div class="p-4 bg-blue-50 rounded-md text-gray-800 border-l-4 border-[#1A7EFB]">
                                    {{ $kinerja->target_kinerja }}
                                </div>
                            </div>
                            
                            @if($latestDetail = $kinerja->details->last())
                                <div>
                                    <p class="text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">Realisasi</p>
                                    <div class="p-4 bg-green-50 rounded-md text-gray-800 border-l-4 border-green-500">
                                        {{ $latestDetail->realisasi_target }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Footer Kartu --}}
                        <div class="px-6 pb-4 pt-4 border-t border-gray-200 flex justify-between items-center bg-gray-50">
                            <div class="flex space-x-2">
                                <form action="{{ route('kinerja.destroy', $kinerja) }}" method="POST" 
                                      onsubmit="return confirm('PERINGATAN: Aksi ini akan menghapus kegiatan utama beserta semua detail progresnya. Lanjutkan?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-md transition-colors duration-200" 
                                            title="Hapus Kegiatan">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            
                            <a href="{{ route('kinerja.show', $kinerja) }}" 
                               class="inline-flex items-center text-[#1A7EFB] hover:text-blue-700 font-semibold text-sm transition-colors duration-200">
                                Lihat Detail
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-gray-500 text-lg">Belum ada data realisasi kegiatan untuk bulan ini.</p>
                        <p class="text-gray-400 text-sm mt-2">Klik tombol "Tambah Kegiatan Baru" untuk memulai.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    {{-- Modal Form Tambah Kegiatan --}}
    <div id="formModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            
            {{-- Background Overlay --}}
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>

            {{-- Center Modal --}}
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <form id="dataForm" method="POST" action="{{ route('kinerja.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- Modal Header --}}
                    <div class="bg-gradient-to-r from-[#1A7EFB] to-blue-600 px-6 py-4">
                        <h3 class="text-xl font-bold text-white" id="modalTitle">Tambah Kegiatan Baru</h3>
                    </div>

                    {{-- Modal Body --}}
                    <div class="bg-white px-6 py-6 max-h-[70vh] overflow-y-auto">
                        <div class="space-y-6">
                            
                            {{-- Informasi Kegiatan Utama --}}
                            <div class="p-5 border-2 border-blue-100 rounded-lg bg-blue-50">
                                <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                    Informasi Kegiatan Utama
                                </h4>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="judul_kegiatan" class="block text-sm font-semibold text-gray-700 mb-1">
                                            Judul Kegiatan <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="judul_kegiatan" id="judul_kegiatan" 
                                               class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                                               placeholder="Masukkan judul kegiatan" required>
                                    </div>
                                    
                                    <div>
                                        <label for="target_kinerja" class="block text-sm font-semibold text-gray-700 mb-1">
                                            Target Kinerja <span class="text-red-500">*</span>
                                        </label>
                                        <textarea name="target_kinerja" id="target_kinerja" rows="3" 
                                                  class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                                                  placeholder="Jelaskan target kinerja yang ingin dicapai" required></textarea>
                                    </div>
                                    
                                    <div>
                                        <label for="bulan_tahun" class="block text-sm font-semibold text-gray-700 mb-1">
                                            Bulan & Tahun Laporan <span class="text-red-500">*</span>
                                        </label>
                                        <input type="month" name="bulan_tahun" id="bulan_tahun" 
                                               class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                                               value="{{ $currentDate->format('Y-m') }}" required>
                                    </div>
                                </div>
                            </div>

                            {{-- Laporan Progres Pertama --}}
                            <div class="p-5 border-2 border-green-100 rounded-lg bg-green-50">
                                <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    Laporan Progres Pertama
                                </h4>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="pelaksana" class="block text-sm font-semibold text-gray-700 mb-1">
                                            Pelaksana <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="pelaksana" id="pelaksana" 
                                               class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                                               placeholder="Nama pelaksana kegiatan" required>
                                    </div>
                                    
                                    <div>
                                        <label for="deskripsi_pekerjaan" class="block text-sm font-semibold text-gray-700 mb-1">
                                            Deskripsi Pekerjaan <span class="text-red-500">*</span>
                                        </label>
                                        <textarea name="deskripsi_pekerjaan" id="deskripsi_pekerjaan" rows="3" 
                                                  class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                                                  placeholder="Jelaskan pekerjaan yang dilakukan" required></textarea>
                                    </div>
                                    
                                    <div>
                                        <label for="realisasi_target" class="block text-sm font-semibold text-gray-700 mb-1">
                                            Realisasi Target <span class="text-red-500">*</span>
                                        </label>
                                        <textarea name="realisasi_target" id="realisasi_target" rows="2" 
                                                  class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                                                  placeholder="Jelaskan realisasi dari target yang ditetapkan" required></textarea>
                                    </div>
                                    
                                    <div>
                                        <label for="progres_kegiatan" class="block text-sm font-semibold text-gray-700 mb-1">
                                            Progres Kegiatan (%) <span class="text-red-500">*</span>
                                        </label>
                                        <textarea name="progres_kegiatan" id="progres_kegiatan" rows="2" 
                                                  class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                                                  placeholder="Contoh: 75% - Tahap implementasi sudah selesai" required></textarea>
                                    </div>
                                    
                                    <div>
                                        <label for="kendala" class="block text-sm font-semibold text-gray-700 mb-1">
                                            Kendala
                                        </label>
                                        <textarea name="kendala" id="kendala" rows="2" 
                                                  class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                                                  placeholder="Jelaskan kendala yang dihadapi (jika ada)"></textarea>
                                    </div>
                                    
                                    <div>
                                        <label for="strategi_penyelesaian" class="block text-sm font-semibold text-gray-700 mb-1">
                                            Strategi Penyelesaian
                                        </label>
                                        <textarea name="strategi_penyelesaian" id="strategi_penyelesaian" rows="2" 
                                                  class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                                                  placeholder="Jelaskan strategi untuk mengatasi kendala"></textarea>
                                    </div>
                                    
                                    <div>
                                        <label for="file_bukti" class="block text-sm font-semibold text-gray-700 mb-1">
                                            Upload Dokumentasi
                                        </label>
                                        <input type="file" name="file_bukti" id="file_bukti" 
                                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF (Maks. 5MB)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse gap-3 border-t border-gray-200">
                        <button type="submit" 
                                class="w-full inline-flex justify-center items-center rounded-md border border-transparent shadow-sm px-6 py-2.5 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan
                        </button>
                        <button type="button" onclick="closeModal()" 
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto transition-colors duration-200">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const modal = document.getElementById('formModal');
        
        function openModal() {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    </script>
    @endpush
</x-app-layout>
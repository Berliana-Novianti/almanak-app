<?php

namespace App\Http\Controllers;

use App\Models\Kinerja; // Pastikan nama model Anda benar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\KinerjaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Carbon; // Ditambahkan untuk penanganan tanggal

class KinerjaController extends Controller
{
    /**
     * Menampilkan halaman utama Realisasi Kegiatan,
     * termasuk form tambah dan tabel data milik pengguna.
     */
    public function index(Request $request)
    {
        $selectedMonth = $request->input('bulan', Carbon::now()->format('Y-m'));
        $date = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();

        $kinerjaBulanan = Kinerja::whereYear('bulan_tahun', $date->year)
                                    ->whereMonth('bulan_tahun', $date->month)
                                    ->with('details')
                                    ->latest()
                                    ->get();

        return view('kinerja.index', [
            'kinerjaBulanan' => $kinerjaBulanan,
            'currentDate' => $date,
        ]);
    }

    /**
     * Menyimpan data Kegiatan Utama baru beserta detail pertamanya.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'target_kinerja' => 'required|string',
            'bulan_tahun' => 'required|date_format:Y-m',
            'pelaksana' => 'required|string',
            'deskripsi_pekerjaan' => 'required|string',
            'realisasi_target' => 'required|string',
            'progres_kegiatan' => 'required|string',
            'kendala' => 'nullable|string',
            'strategi_penyelesaian' => 'nullable|string',
            'file_bukti' => 'nullable|file|mimes:pdf,jpg,png,docx|max:2048',
        ]);

        // Buat Kegiatan Utama
        $kinerja = Kinerja::create([
            'user_id' => Auth::id(),
            'judul_kegiatan' => $validated['judul_kegiatan'],
            'target_kinerja' => $validated['target_kinerja'],
            'bulan_tahun' => $validated['bulan_tahun'] . '-01', // Tambah hari agar jadi format tanggal valid
        ]);

        $filePath = null;
        if ($request->hasFile('file_bukti')) {
            $filePath = $request->file('file_bukti')->store('bukti_kinerja', 'public');
        }

        // Buat Detail Kinerja yang pertama
        $kinerja->details()->create([
            'pelaksana' => $validated['pelaksana'],
            'deskripsi_pekerjaan' => $validated['deskripsi_pekerjaan'],
            'realisasi_target' => $validated['realisasi_target'],
            'progres_kegiatan' => $validated['progres_kegiatan'],
            'kendala' => $validated['kendala'],
            'strategi_penyelesaian' => $validated['strategi_penyelesaian'],
            'file_bukti' => $filePath,
        ]);

        return back()->with('success', 'Realisasi kegiatan baru berhasil ditambahkan.');
    }

    public function show(Kinerja $kinerja)
    {
        // Memuat relasi 'details' untuk ditampilkan di view
        $kinerja->load('details');

        return view('kinerja.show', compact('kinerja'));
    }

    public function update(Request $request, Kinerja $kinerja)
    {
        $validated = $request->validate([
            'pelaksana'              => 'required|string|max:255',
            'deskripsi_pekerjaan'   => 'required|string',
            'realisasi_target'      => 'required|string',
            'progres_kegiatan'      => 'required|string',
        ]);
        
        $kinerja->update($validated);
        
        return back()->with('success', 'Kegiatan utama berhasil diperbarui.');
    }

    /**
     * Menghapus data realisasi kegiatan.
     */
    public function destroy(Kinerja $kinerja)
    {
        foreach ($kinerja->details as $detail) {
            if ($detail->file_bukti) {
                Storage::disk('public')->delete($detail->file_bukti);
            }
        }

        $kinerja->delete();
        return redirect()->route('kinerja.index')->with('success', 'Laporan Realisasi Kegiatan berhasil dihapus.');
    }

    /**
     * Menangani permintaan ekspor data ke Excel.
     */
    public function exportExcel(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2023',
            'month' => 'required|integer|between:1,12',
        ]);

        $year = $request->year;
        $month = $request->month;
        $user = Auth::user();

        // Buat nama file yang dinamis
        $fileName = 'Laporan Realisasi - ' . $user->name . ' - ' . \Carbon\Carbon::create()->month($month)->translatedFormat('F') . ' ' . $year . '.xlsx';

        // Panggil kelas KinerjaExport untuk menghasilkan dan mengunduh file
        return Excel::download(new KinerjaExport($year, $month, $user->id), $fileName);
    }
}


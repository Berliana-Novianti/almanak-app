<?php

namespace App\Exports;

use App\Models\Kinerja; // Pastikan nama model Anda benar
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KinerjaExport implements FromQuery, WithHeadings, WithMapping
{
    protected $year;
    protected $month;
    protected $userId;

    public function __construct(int $year, int $month, int $userId)
    {
        $this->year = $year;
        $this->month = $month;
        $this->userId = $userId;
    }

    /**
    * @return \Illuminate\Database\Query\Builder
    */
    public function query()
    {
        // Ambil data dari database berdasarkan user, tahun, dan bulan
        return Kinerja::query()
            ->where('user_id', $this->userId)
            ->whereYear('tanggal_kegiatan', $this->year) // Ganti 'tanggal_kegiatan' jika nama kolom berbeda
            ->whereMonth('tanggal_kegiatan', $this->month);
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        // Ini adalah judul kolom di file Excel
        return [
            'Tanggal Kegiatan',
            'Nama Kegiatan',
            'Jumlah',
            'Satuan',
            'Keterangan',
        ];
    }

    /**
    * @param Kinerja $kinerja
    * @return array
    */
    public function map($kinerja): array
    {
        // Ini memetakan setiap baris data ke kolom yang sesuai
        return [
            $kinerja->tanggal_kegiatan->format('d-m-Y'),
            $kinerja->nama_kegiatan,
            $kinerja->jumlah,
            $kinerja->satuan,
            $kinerja->keterangan,
        ];
    }
}
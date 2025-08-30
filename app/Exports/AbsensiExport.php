<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $absensi;

    public function __construct($absensi)
    {
        $this->absensi = $absensi;
    }

    public function collection()
    {
        return $this->absensi;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Jam Masuk',
            'Jam Keluar',
            'Jenis Pengunjung',
            'Nama',
            'Instansi/Kelas',
            'No. Telepon',
            'Status Kunjungan',
            'Keperluan',
            'Keterangan'
        ];
    }

    public function map($absensi): array
    {
        static $no = 1;
        
        $nama = $absensi->jenis_pengunjung === 'anggota' && $absensi->anggota
            ? $absensi->anggota->nama_lengkap
            : $absensi->nama_tamu;

        $instansi = $absensi->jenis_pengunjung === 'anggota' && $absensi->anggota && $absensi->anggota->kelas
            ? $absensi->anggota->kelas->nama_kelas . ' - ' . $absensi->anggota->kelas->jurusan->nama_jurusan
            : $absensi->instansi;

        $noTelepon = $absensi->jenis_pengunjung === 'anggota' && $absensi->anggota
            ? $absensi->anggota->no_telepon
            : $absensi->no_telepon;

        return [
            $no++,
            $absensi->tanggal ? $absensi->tanggal->format('d/m/Y') : '-',
            $absensi->jam_masuk ?: '-',
            $absensi->jam_keluar ?: '-',
            ucfirst($absensi->jenis_pengunjung),
            $nama,
            $instansi ?: '-',
            $noTelepon ?: '-',
            ucfirst($absensi->status_kunjungan ?: ''),
            $absensi->keperluan ?: '-',
            $absensi->keterangan ?: '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F3E5F5']
                ]
            ]
        ];
    }
}
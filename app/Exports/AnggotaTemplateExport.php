<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Kelas;

class AnggotaTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function array(): array
    {
        $kelas = Kelas::with('jurusan')->get();
        
        $sampleData = [
            [
                'John Doe',
                'Laki-laki',
                '1234567890123456',
                'Jl. Contoh No. 123, Kota Contoh',
                '081234567890',
                'john.doe@example.com',
                $kelas->first() ? $kelas->first()->id : '1', // ID Kelas - lihat daftar kelas di bawah
                'Siswa',
                'siswa', // siswa/guru/staff
                'aktif', // aktif/nonaktif/ditangguhkan
                '2024-01-01'
            ],
            [
                'Jane Smith',
                'Perempuan',
                '9876543210987654',
                'Jl. Sample No. 456, Kota Sample',
                '089876543210',
                'jane.smith@example.com',
                $kelas->count() > 1 ? $kelas->get(1)->id : ($kelas->first() ? $kelas->first()->id : '1'), // ID Kelas
                'Guru',
                'guru',
                'aktif',
                '2024-01-15'
            ]
        ];

        // Tambahkan data kelas untuk referensi
        $kelasData = [];
        if ($kelas->count() > 0) {
            foreach ($kelas as $k) {
                $kelasData[] = [
                    'ID: ' . $k->id . ' - ' . $k->nama_kelas . ' (' . $k->jurusan->nama_jurusan . ')',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $k->id,
                    '',
                    '',
                    '',
                    ''
                ];
            }
        } else {
            $kelasData[] = [
                'Belum ada data kelas. Silakan tambahkan kelas terlebih dahulu.',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ];
        }

        return array_merge($sampleData, [[''], [''], ['DAFTAR KELAS UNTUK REFERENSI:'], ['']], $kelasData);
    }

    public function headings(): array
    {
        return [
            'nama_lengkap',
            'jenis_kelamin',
            'nik',
            'alamat',
            'nomor_telepon',
            'email',
            'kelas_id',
            'jabatan',
            'jenis_anggota',
            'status',
            'tanggal_bergabung'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E8F5E8']
                ]
            ],
            // Style the reference section
            'A' . (count($this->array()) - count($this->getKelasData()) + 1) => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFF3CD']
                ]
            ],
        ];
    }

    private function getKelasData()
    {
        $kelas = Kelas::with('jurusan')->get();
        $kelasData = [];
        foreach ($kelas as $k) {
            $kelasData[] = [
                'ID: ' . $k->id . ' - ' . $k->nama_kelas . ' (' . $k->jurusan->nama_jurusan . ')',
                '',
                '',
                '',
                '',
                '',
                $k->id,
                '',
                '',
                '',
                ''
            ];
        }
        return $kelasData;
    }
} 
<?php

namespace App\Exports;

use App\Models\Buku;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;

class BukuExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Buku::with(['kategoriBuku', 'jenisBuku', 'sumberBuku', 'rakBuku']);

        if ($this->request) {
            // Apply search filter
            if ($this->request->filled('search')) {
                $search = $this->request->search;
                $query->where(function($q) use ($search) {
                    $q->where('judul_buku', 'LIKE', "%{$search}%")
                      ->orWhere('isbn', 'LIKE', "%{$search}%")
                      ->orWhere('barcode', 'LIKE', "%{$search}%")
                      ->orWhere('pengarang', 'LIKE', "%{$search}%")
                      ->orWhere('penerbit', 'LIKE', "%{$search}%")
                      ->orWhereHas('kategoriBuku', function($q) use ($search) {
                          $q->where('nama_kategori', 'LIKE', "%{$search}%");
                      });
                });
            }

            // Apply kategori filter
            if ($this->request->filled('kategori_id')) {
                $query->where('kategori_id', $this->request->kategori_id);
            }

            // Apply jenis filter
            if ($this->request->filled('jenis_id')) {
                $query->where('jenis_id', $this->request->jenis_id);
            }

            // Apply status filter
            if ($this->request->filled('status')) {
                $query->where('status', $this->request->status);
            }

            // Apply stok filter
            if ($this->request->filled('stok')) {
                if ($this->request->stok === 'tersedia') {
                    $query->where('stok_tersedia', '>', 0);
                } elseif ($this->request->stok === 'habis') {
                    $query->where('stok_tersedia', '<=', 0);
                }
            }
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Judul Buku',
            'ISBN',
            'Barcode',
            'Penulis',
            'Penerbit',
            'Kategori',
            'Jenis',
            'Sumber',
            'Tahun Terbit',
            'Jumlah Halaman',
            'Bahasa',
            'Jumlah Stok',
            'Stok Tersedia',
            'Lokasi Rak',
            'Gambar Sampul',
            'Status',
            'Deskripsi',
            'Tanggal Dibuat'
        ];
    }

    public function map($buku): array
    {
        static $no = 1;
        
        return [
            $no++,
            $buku->judul_buku,
            $buku->isbn ?? '-',
            $buku->barcode ?? '-',
            $buku->pengarang ?? '-',
            $buku->penerbit ?? '-',
            $buku->kategoriBuku ? $buku->kategoriBuku->nama_kategori : '-',
            $buku->jenisBuku ? $buku->jenisBuku->nama_jenis : '-',
            $buku->sumberBuku ? $buku->sumberBuku->nama_sumber : '-',
            $buku->tahun_terbit ?? '-',
            $buku->jumlah_halaman ?? '-',
            $buku->bahasa ?? 'Indonesia',
            $buku->jumlah_stok,
            $buku->stok_tersedia,
            $buku->lokasi_rak ?? '-',
            $buku->gambar_sampul ?? '-',
            ucfirst($buku->status),
            $buku->deskripsi ?? '-',
            $buku->created_at->format('d/m/Y H:i:s')
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
                    'startColor' => ['rgb' => 'E3F2FD']
                ]
            ],
        ];
    }
}

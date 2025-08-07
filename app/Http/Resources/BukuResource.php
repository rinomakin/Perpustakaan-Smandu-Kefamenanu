<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BukuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'judul' => $this->judul,
            'isbn' => $this->isbn,
            'tahun_terbit' => $this->tahun_terbit,
            'jumlah_halaman' => $this->jumlah_halaman,
            'status' => $this->status,
            'status_text' => $this->status ? 'Tersedia' : 'Dipinjam',
            'created_at' => $this->created_at?->format('d-m-Y H:i:s'),
            'updated_at' => $this->updated_at?->format('d-m-Y H:i:s'),
            'penulis' => $this->penulis,
            'penerbit' => $this->penerbit,
            'jenis_buku' => $this->whenLoaded('jenisBuku', function () {
                return [
                    'id' => $this->jenisBuku->id,
                    'nama_jenis' => $this->jenisBuku->nama_jenis,
                    'kode_jenis' => $this->jenisBuku->kode_jenis,
                ];
            }),
        ];
    }
} 
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JenisBukuResource extends JsonResource
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
            'nama_jenis' => $this->nama_jenis,
            'kode_jenis' => $this->kode_jenis,
            'deskripsi' => $this->deskripsi,
            'status' => $this->status,
            'status_text' => $this->status ? 'Aktif' : 'Tidak Aktif',
            'jumlah_buku' => $this->whenLoaded('buku', function () {
                return $this->buku->count();
            }),
            'created_at' => $this->created_at?->format('d-m-Y H:i:s'),
            'updated_at' => $this->updated_at?->format('d-m-Y H:i:s'),
            'buku' => BukuResource::collection($this->whenLoaded('buku')),
        ];
    }
} 
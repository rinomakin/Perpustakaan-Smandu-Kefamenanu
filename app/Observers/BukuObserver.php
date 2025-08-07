<?php

namespace App\Observers;

use App\Models\Buku;
use App\Models\RakBuku;

class BukuObserver
{
    /**
     * Handle the Buku "created" event.
     */
    public function created(Buku $buku): void
    {
        // Update jumlah buku di rak jika buku ditambahkan ke rak
        if ($buku->rak_id) {
            $rak = RakBuku::find($buku->rak_id);
            if ($rak) {
                $rak->updateJumlahBuku();
            }
        }
    }

    /**
     * Handle the Buku "updated" event.
     */
    public function updated(Buku $buku): void
    {
        // Jika rak_id berubah, update jumlah buku di rak lama dan baru
        if ($buku->wasChanged('rak_id')) {
            $oldRakId = $buku->getOriginal('rak_id');
            $newRakId = $buku->rak_id;

            // Update rak lama
            if ($oldRakId) {
                $oldRak = RakBuku::find($oldRakId);
                if ($oldRak) {
                    $oldRak->updateJumlahBuku();
                }
            }

            // Update rak baru
            if ($newRakId) {
                $newRak = RakBuku::find($newRakId);
                if ($newRak) {
                    $newRak->updateJumlahBuku();
                }
            }
        }
    }

    /**
     * Handle the Buku "deleted" event.
     */
    public function deleted(Buku $buku): void
    {
        // Update jumlah buku di rak jika buku dihapus dari rak
        if ($buku->rak_id) {
            $rak = RakBuku::find($buku->rak_id);
            if ($rak) {
                $rak->updateJumlahBuku();
            }
        }
    }

    /**
     * Handle the Buku "restored" event.
     */
    public function restored(Buku $buku): void
    {
        // Update jumlah buku di rak jika buku dipulihkan
        if ($buku->rak_id) {
            $rak = RakBuku::find($buku->rak_id);
            if ($rak) {
                $rak->updateJumlahBuku();
            }
        }
    }

    /**
     * Handle the Buku "force deleted" event.
     */
    public function forceDeleted(Buku $buku): void
    {
        // Update jumlah buku di rak jika buku dihapus permanen
        if ($buku->rak_id) {
            $rak = RakBuku::find($buku->rak_id);
            if ($rak) {
                $rak->updateJumlahBuku();
            }
        }
    }
}

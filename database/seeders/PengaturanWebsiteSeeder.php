<?php

namespace Database\Seeders;

use App\Models\PengaturanWebsite;
use Illuminate\Database\Seeder;

class PengaturanWebsiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PengaturanWebsite::create([
            'nama_website' => 'SIPERPUS',
            'logo' => null,
            'favicon' => null,
            'deskripsi_website' => 'SMAN 2 KEFAMENANU',
            'alamat_sekolah' => 'Jl. Soekarno-Hatta No. 1, Kefamenanu, Timor Tengah Utara, NTT',
            'telepon_sekolah' => '(0388) 123456',
            'email_sekolah' => 'sman2kefamenanu@gmail.com',
            'nama_kepala_sekolah' => 'Drs. John Doe, M.Pd',
            'visi_sekolah' => 'Terwujudnya sekolah unggul yang menghasilkan lulusan berkualitas, berakhlak mulia, dan siap menghadapi tantangan global',
            'misi_sekolah' => '1. Menyelenggarakan pendidikan berkualitas dengan standar nasional dan internasional\n2. Mengembangkan potensi peserta didik secara optimal\n3. Menumbuhkan karakter dan akhlak mulia\n4. Mempersiapkan lulusan yang siap menghadapi tantangan global',
            'sejarah_sekolah' => 'SMAN 1 Kefamenanu didirikan pada tahun 1980 dan telah menjadi salah satu sekolah menengah atas terbaik di Kabupaten Timor Tengah Utara.',
            'jam_operasional' => 'Senin - Jumat: 07:00 - 15:00 WITA',
            'kebijakan_perpustakaan' => '1. Setiap siswa dapat meminjam maksimal 2 buku\n2. Masa pinjam 7 hari\n3. Denda keterlambatan Rp 1.000 per hari',
        ]);
    }
} 
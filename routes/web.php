<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\KepsekController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\AbsensiPengunjungController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KategoriBukuController;
use App\Http\Controllers\JenisBukuController;
use App\Http\Controllers\SumberBukuController;
use App\Http\Controllers\PenerbitController;
use App\Http\Controllers\PenulisController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\CetakController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Frontend Routes (untuk petugas menampilkan ke siswa/guru)
Route::middleware(['auth', 'role:petugas'])->prefix('frontend')->group(function () {
    Route::get('/', [FrontendController::class, 'index'])->name('frontend.home');
    Route::get('/cari-buku', [FrontendController::class, 'cariBuku'])->name('frontend.cari.buku');
    Route::get('/tentang', [FrontendController::class, 'tentang'])->name('frontend.tentang');
    Route::get('/koleksi-buku', [FrontendController::class, 'koleksiBuku'])->name('frontend.koleksi');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/pengaturan-website', [AdminController::class, 'pengaturanWebsite'])->name('admin.pengaturan');
    Route::post('/pengaturan-website', [AdminController::class, 'updatePengaturanWebsite'])->name('admin.pengaturan.update');
    
    // CRUD Anggota
    Route::resource('anggota', AnggotaController::class);
    Route::post('/anggota/bulk-delete', [AnggotaController::class, 'bulkDelete'])->name('anggota.bulk-delete');
    Route::get('/anggota/export', [AnggotaController::class, 'export'])->name('anggota.export');
    Route::get('/anggota/download-template', [AnggotaController::class, 'downloadTemplate'])->name('anggota.download-template');
    Route::post('/anggota/import', [AnggotaController::class, 'import'])->name('anggota.import');
    Route::get('/anggota/cetak-kartu/{id}', [AnggotaController::class, 'cetakKartu'])->name('anggota.cetak-kartu');
    Route::post('/anggota/scan-barcode', [AnggotaController::class, 'scanBarcode'])->name('anggota.scan-barcode');
    
    // CRUD Buku
    Route::resource('buku', BukuController::class);
    
    // CRUD Jurusan
    Route::resource('jurusan', JurusanController::class);
    
    // CRUD Kelas
    Route::resource('kelas', KelasController::class);
    Route::post('/kelas/generate-kode', [KelasController::class, 'generateKodeKelas'])->name('kelas.generate-kode');
    
    // CRUD Kategori Buku
    Route::resource('kategori-buku', KategoriBukuController::class);
    
    // CRUD Jenis Buku
    Route::resource('jenis-buku', JenisBukuController::class);
    Route::get('/jenis-buku/export', [JenisBukuController::class, 'export'])->name('jenis-buku.export');
    Route::post('/jenis-buku/bulk-delete', [JenisBukuController::class, 'bulkDelete'])->name('jenis-buku.bulk-delete');
    
    // CRUD Sumber Buku
    Route::resource('sumber-buku', SumberBukuController::class);
    
    // CRUD Penerbit
    Route::resource('penerbit', PenerbitController::class);
    
    // CRUD Penulis
    Route::resource('penulis', PenulisController::class);
    
    // CRUD Peminjaman
    Route::resource('peminjaman', PeminjamanController::class);
    
    // CRUD Denda
    Route::resource('denda', DendaController::class);
    
    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/anggota', [LaporanController::class, 'anggota'])->name('admin.laporan.anggota');
    Route::get('/laporan/buku', [LaporanController::class, 'buku'])->name('admin.laporan.buku');
    Route::get('/laporan/kas', [LaporanController::class, 'kas'])->name('admin.laporan.kas');
    
    // Cetak
    Route::get('/cetak/kartu-anggota/{id}', [CetakController::class, 'kartuAnggota'])->name('admin.cetak.kartu');
    Route::get('/cetak/label-buku/{id}', [CetakController::class, 'labelBuku'])->name('admin.cetak.label');
});

// Petugas Routes
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->group(function () {
    Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('petugas.dashboard');
    Route::get('/beranda', [PetugasController::class, 'beranda'])->name('petugas.beranda');
    Route::get('/tentang', [PetugasController::class, 'tentang'])->name('petugas.tentang');
    Route::resource('absensi-pengunjung', AbsensiPengunjungController::class)->names([
        'index' => 'petugas.absensi-pengunjung.index',
        'create' => 'petugas.absensi-pengunjung.create',
        'store' => 'petugas.absensi-pengunjung.store',
        'show' => 'petugas.absensi-pengunjung.show',
        'edit' => 'petugas.absensi-pengunjung.edit',
        'update' => 'petugas.absensi-pengunjung.update',
        'destroy' => 'petugas.absensi-pengunjung.destroy',
    ]);
    Route::post('/absensi-pengunjung/scan-qr', [AbsensiPengunjungController::class, 'scanQR'])->name('petugas.absensi-pengunjung.scan-qr');
});

// Kepala Sekolah Routes
Route::middleware(['auth', 'role:kepala_sekolah'])->prefix('kepsek')->group(function () {
    Route::get('/dashboard', [KepsekController::class, 'dashboard'])->name('kepsek.dashboard');
    Route::get('/laporan', [KepsekController::class, 'laporan'])->name('kepsek.laporan');
});

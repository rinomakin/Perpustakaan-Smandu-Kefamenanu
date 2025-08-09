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
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\CetakController;
use App\Http\Controllers\RiwayatPeminjamanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

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

// API Routes untuk AJAX (tanpa middleware admin)
Route::middleware(['auth'])->group(function () {
    // Routes ini akan dipindah ke admin group
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/pengaturan-website', [AdminController::class, 'pengaturanWebsite'])->name('admin.pengaturan');
    Route::post('/pengaturan-website', [AdminController::class, 'updatePengaturanWebsite'])->name('admin.pengaturan.update');
    
    // API Routes untuk AJAX pencarian
    Route::get('/peminjaman/search-anggota', [PeminjamanController::class, 'searchAnggota'])->name('peminjaman.search-anggota');
    Route::get('/peminjaman/search-buku', [PeminjamanController::class, 'searchBuku'])->name('peminjaman.search-buku');
    
    // Route test untuk debugging
    Route::get('/test-search', function() {
        return response()->json(['success' => true, 'message' => 'Route test berhasil']);
    })->name('test.search');
    
    // CRUD Anggota
    Route::get('/anggota/export', [AnggotaController::class, 'export'])->name('anggota.export');
    Route::get('/anggota/download-template', [AnggotaController::class, 'downloadTemplate'])->name('anggota.download-template');
    Route::post('/anggota/import', [AnggotaController::class, 'import'])->name('anggota.import');
    Route::post('/anggota/bulk-delete', [AnggotaController::class, 'bulkDelete'])->name('anggota.bulk-delete');
    Route::get('/anggota/cetak-kartu/{id}', [AnggotaController::class, 'cetakKartu'])->name('anggota.cetak-kartu');
Route::get('/anggota/bulk-print-kartu', [AnggotaController::class, 'bulkPrintKartu'])->name('anggota.bulk-print-kartu');
    Route::post('/anggota/scan-barcode', [AnggotaController::class, 'scanBarcode'])->name('anggota.scan-barcode');
    Route::post('/anggota/generate-barcode', [AnggotaController::class, 'generateBarcode'])->name('anggota.generate-barcode');
    Route::post('/anggota/clean-duplicates', [AnggotaController::class, 'cleanDuplicateData'])->name('anggota.clean-duplicates');
    Route::post('/anggota/regenerate-duplicates', [AnggotaController::class, 'regenerateDuplicateCodes'])->name('anggota.regenerate-duplicates');
    Route::post('/anggota/clean-and-regenerate', [AnggotaController::class, 'cleanAndRegenerateDuplicates'])->name('anggota.clean-and-regenerate');
    Route::get('/anggota/check-duplicates', [AnggotaController::class, 'checkDuplicateData'])->name('anggota.check-duplicates');
    Route::get('/anggota/import-stats', [AnggotaController::class, 'getImportStats'])->name('anggota.import-stats');
    Route::resource('anggota', AnggotaController::class);
    
    // CRUD Buku - Routes khusus harus ditempatkan sebelum resource route
    Route::get('/buku/export', [BukuController::class, 'export'])->name('buku.export');
    Route::get('/buku/download-template', [BukuController::class, 'downloadTemplate'])->name('buku.download-template');
    Route::post('/buku/import', [BukuController::class, 'import'])->name('buku.import');
    Route::post('/buku/generate-barcode', [BukuController::class, 'generateBarcode'])->name('buku.generate-barcode');
    Route::post('/buku/generate-multiple-barcode', [BukuController::class, 'generateMultipleBarcode'])->name('buku.generate-multiple-barcode');
    Route::get('/buku/{id}/print-barcode', [BukuController::class, 'printBarcode'])->name('buku.print-barcode');
    Route::get('/buku/{id}/cetak-barcode', [BukuController::class, 'cetakBarcode'])->name('buku.cetak-barcode');
    Route::post('/buku/print-multiple-barcode', [BukuController::class, 'printMultipleBarcode'])->name('buku.print-multiple-barcode');
    Route::post('/buku/scan-barcode', [BukuController::class, 'scanBarcode'])->name('buku.scan-barcode');
    Route::delete('/buku/destroy-multiple', [BukuController::class, 'destroyMultiple'])->name('buku.destroy-multiple');
    
    // Resource route harus di bawah routes khusus
    Route::resource('buku', BukuController::class);
    
    // CRUD Jurusan
    Route::resource('jurusan', JurusanController::class);
    
    // CRUD Kelas
    Route::resource('kelas', KelasController::class);
    Route::post('/kelas/generate-kode', [KelasController::class, 'generateKodeKelas'])->name('kelas.generate-kode');
    
    // CRUD Kategori Buku
    Route::resource('kategori-buku', KategoriBukuController::class);
    Route::post('/kategori-buku/generate-kode', [KategoriBukuController::class, 'generateKode'])->name('kategori-buku.generate-kode');
    Route::delete('/kategori-buku/destroy-multiple', [KategoriBukuController::class, 'destroyMultiple'])->name('kategori-buku.destroy-multiple');
    
    // CRUD Jenis Buku
    Route::resource('jenis-buku', JenisBukuController::class);
    Route::get('/jenis-buku/export', [JenisBukuController::class, 'export'])->name('jenis-buku.export');
    Route::post('/jenis-buku/bulk-delete', [JenisBukuController::class, 'bulkDelete'])->name('jenis-buku.bulk-delete');
    
    // CRUD Sumber Buku
    Route::post('/sumber-buku/generate-kode', [SumberBukuController::class, 'generateKode'])->name('sumber-buku.generate-kode');
    Route::delete('/sumber-buku/destroy-multiple', [SumberBukuController::class, 'destroyMultiple'])->name('sumber-buku.destroy-multiple');
    Route::resource('sumber-buku', SumberBukuController::class);
    
    // CRUD Rak Buku
    Route::resource('rak-buku', \App\Http\Controllers\Admin\RakBukuController::class);
    Route::get('/rak-buku/get-rak', [\App\Http\Controllers\Admin\RakBukuController::class, 'getRakBuku'])->name('rak-buku.get-rak');
    
    // CRUD Role
    Route::resource('role', RoleController::class);
    Route::post('/role/generate-kode', [RoleController::class, 'generateKode'])->name('role.generate-kode');
    
    // CRUD User
    Route::resource('user', UserController::class);
    Route::post('/user/{user}/reset-password', [UserController::class, 'resetPassword'])->name('user.reset-password');
    
    // CRUD Peminjaman
    Route::resource('peminjaman', PeminjamanController::class);
    Route::post('/peminjaman/scan-anggota', [PeminjamanController::class, 'scanAnggota'])->name('peminjaman.scan-anggota');
    Route::post('/peminjaman/scan-buku', [PeminjamanController::class, 'scanBuku'])->name('peminjaman.scan-buku');
    Route::post('/peminjaman/scan-multiple-buku', [PeminjamanController::class, 'scanMultipleBuku'])->name('peminjaman.scan-multiple-buku');
    
    // CRUD Pengembalian
    Route::resource('pengembalian', \App\Http\Controllers\PengembalianController::class);
    Route::get('/pengembalian/search-anggota', [\App\Http\Controllers\PengembalianController::class, 'searchAnggota'])->name('pengembalian.search-anggota');
    Route::get('/pengembalian/get-peminjaman-aktif', [\App\Http\Controllers\PengembalianController::class, 'getPeminjamanAktif'])->name('pengembalian.get-peminjaman-aktif');
    Route::post('/pengembalian/scan-barcode', [\App\Http\Controllers\PengembalianController::class, 'scanBarcode'])->name('pengembalian.scan-barcode');
    
    // CRUD Denda
    Route::resource('denda', DendaController::class);
    
    // CRUD Absensi Pengunjung
    Route::resource('absensi-pengunjung', AbsensiPengunjungController::class);
    
    // Riwayat Peminjaman
    Route::get('/riwayat-peminjaman', [RiwayatPeminjamanController::class, 'index'])->name('riwayat-peminjaman.index');
    Route::get('/riwayat-peminjaman/export', [RiwayatPeminjamanController::class, 'export'])->name('riwayat-peminjaman.export');
    
    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/anggota', [LaporanController::class, 'anggota'])->name('admin.laporan.anggota');
    Route::get('/laporan/buku', [LaporanController::class, 'buku'])->name('admin.laporan.buku');
    Route::get('/laporan/peminjaman', [LaporanController::class, 'peminjaman'])->name('admin.laporan.peminjaman');
    Route::get('/laporan/denda', [LaporanController::class, 'denda'])->name('admin.laporan.denda');
    Route::get('/laporan/absensi', [LaporanController::class, 'absensi'])->name('admin.laporan.absensi');
    Route::get('/laporan/kas', [LaporanController::class, 'kas'])->name('admin.laporan.kas');
    
    // Cetak
    Route::get('/cetak/label-buku/{id}', [CetakController::class, 'labelBuku'])->name('admin.cetak.label');
    Route::get('/cetak/kartu-anggota/{id}', [CetakController::class, 'kartuAnggota'])->name('admin.cetak.kartu');
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

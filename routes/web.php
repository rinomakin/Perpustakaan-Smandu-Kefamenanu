<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\KepsekController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\BukuTamuController;
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
use App\Http\Controllers\RiwayatPengembalianController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;

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



// Test route for debugging buku-tamu search
Route::get('/test-buku-tamu-route', function() {
    return response()->json([
        'admin_search_url' => route('admin.buku-tamu.search-members'),
        'petugas_search_url' => route('petugas.buku-tamu.search-members'),
        'current_user' => auth()->user() ? auth()->user()->name : 'Not logged in',
        'timestamp' => now()
    ]);
})->name('test.buku.tamu.route');

// Simple test search route without middleware
Route::get('/test-search-simple', [BukuTamuController::class, 'searchMembers'])->name('test.search.simple');

// Simple debug route without admin middleware - for testing access
Route::get('/debug-user-access', function() {
    return response()->json([
        'logged_in' => auth()->check(),
        'user' => auth()->user() ? [
            'id' => auth()->user()->id,
            'name' => auth()->user()->nama_lengkap,
            'email' => auth()->user()->email,
            'role' => auth()->user()->role->nama_peran ?? 'N/A'
        ] : null,
        'timestamp' => now()
    ]);
})->middleware('auth');

// Test searchMembers without role middleware
Route::get('/test-search-members-no-role', [BukuTamuController::class, 'searchMembers'])
    ->middleware('auth')
    ->name('test.search.members.no.role');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route test tanpa middleware sama sekali
Route::get('/test-route', function() {
    return response()->json([
        'success' => true,
        'message' => 'Route tanpa middleware berhasil',
        'timestamp' => now()
    ]);
})->name('test.route');

// Route test search tanpa middleware
Route::get('/test-search', function(\Illuminate\Http\Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'Test search berhasil',
        'query' => $request->get('query', ''),
        'timestamp' => now()
    ]);
})->name('test.search');

// Route test untuk memeriksa data peminjaman aktif
Route::get('/test-peminjaman-aktif', function() {
    try {
        // Cek semua peminjaman dengan status dipinjam
        $peminjamanAktif = \App\Models\Peminjaman::where('status', 'dipinjam')
            ->with(['anggota', 'detailPeminjaman.buku'])
            ->get();
        
        // Cek anggota dengan peminjaman aktif
        $anggotaDenganPeminjaman = \App\Models\Anggota::whereHas('peminjaman', function($q) {
            $q->where('status', 'dipinjam');
        })->count();
        
        return response()->json([
            'success' => true,
            'total_peminjaman_aktif' => $peminjamanAktif->count(),
            'total_anggota_dengan_peminjaman' => $anggotaDenganPeminjaman,
            'sample_peminjaman' => $peminjamanAktif->take(3)->map(function($p) {
                return [
                    'id' => $p->id,
                    'anggota' => $p->anggota ? $p->anggota->nama_lengkap : 'N/A',
                    'status' => $p->status,
                    'detail_count' => $p->detailPeminjaman->count()
                ];
            }),
            'timestamp' => now()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'timestamp' => now()
        ]);
    }
})->name('test.peminjaman.aktif');

// Route search anggota tanpa middleware - untuk testing
Route::get('/search-anggota-test', function(\Illuminate\Http\Request $request) {
    try {
        $query = $request->get('query', '');
        
        // Query sederhana untuk testing
        $anggota = \App\Models\Anggota::with(['kelas', 'jurusan'])
            ->whereHas('peminjaman', function($q) {
                $q->where('status', 'dipinjam');
            })
            ->where(function($q) use ($query) {
                $q->where('nama', 'LIKE', "%{$query}%")
                  ->orWhere('nama_lengkap', 'LIKE', "%{$query}%")
                  ->orWhere('nis', 'LIKE', "%{$query}%")
                  ->orWhere('nomor_anggota', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get();
        
        $result = $anggota->map(function($anggota) {
            $peminjamanAktif = $anggota->peminjaman()->where('status', 'dipinjam')->count();
            
            return [
                'id' => $anggota->id,
                'nama_lengkap' => $anggota->nama_lengkap,
                'nis' => $anggota->nis,
                'nomor_anggota' => $anggota->nomor_anggota,
                'barcode_anggota' => $anggota->barcode_anggota,
                'kelas' => $anggota->kelas ? $anggota->kelas->nama_kelas : 'N/A',
                'jurusan' => $anggota->jurusan ? $anggota->jurusan->nama_jurusan : 'N/A',
                'jenis_anggota' => $anggota->jenis_anggota,
                'jumlah_peminjaman_aktif' => $peminjamanAktif,
                'detail_peminjaman' => []
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $result,
            'debug' => [
                'query' => $query,
                'count' => $result->count(),
                'total_peminjaman_aktif' => $result->sum('jumlah_peminjaman_aktif')
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            'debug' => [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]
        ], 500);
    }
})->name('search.anggota.test');

// Route search anggota menggunakan controller method yang sudah ada
Route::get('/search-anggota-simple', [\App\Http\Controllers\PengembalianController::class, 'searchAnggota'])
    ->name('search.anggota.simple');

// Route test yang sangat sederhana untuk debugging
Route::get('/debug-search', function() {
    return response()->json([
        'success' => true,
        'message' => 'Route debug search berhasil',
        'timestamp' => now(),
        'test_data' => [
            'query' => request()->get('query', ''),
            'user' => auth()->user() ? auth()->user()->name : 'Not logged in'
        ]
    ]);
})->name('debug.search');

// Route test untuk pengembalian search-anggota
Route::get('/test-pengembalian-search', function() {
    return response()->json([
        'success' => true,
        'message' => 'Route pengembalian search berfungsi',
        'timestamp' => now(),
        'route' => 'admin.pengembalian.search-anggota'
    ]);
})->name('test.pengembalian.search');

// Route test model Anggota yang sangat sederhana
Route::get('/test-anggota-basic', function() {
    try {
        $anggota = \App\Models\Anggota::first();
        return response()->json([
            'success' => true,
            'message' => 'Model Anggota berfungsi',
            'sample_anggota' => $anggota ? [
                'id' => $anggota->id,
                'nama' => $anggota->nama,
                'nama_lengkap' => $anggota->nama_lengkap
            ] : null,
            'total_anggota' => \App\Models\Anggota::count()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
})->name('test.anggota.basic');

// Route test untuk memeriksa model Anggota
Route::get('/test-anggota-model', function() {
    try {
        $totalAnggota = \App\Models\Anggota::count();
        
        // Test query sederhana untuk anggota dengan nama yang mengandung 'ri'
        $anggotaTest = \App\Models\Anggota::where('nama', 'LIKE', '%ri%')
            ->orWhere('nama_lengkap', 'LIKE', '%ri%')
            ->limit(3)
            ->get(['id', 'nama', 'nama_lengkap', 'nis']);
        
        return response()->json([
            'success' => true,
            'total_anggota' => $totalAnggota,
            'anggota_test' => $anggotaTest,
            'timestamp' => now()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
})->name('test.anggota.model');

// Frontend Routes (untuk petugas menampilkan ke siswa/guru)
Route::middleware(['auth', 'role:PETUGAS'])->prefix('frontend')->group(function () {
    Route::get('/', [FrontendController::class, 'index'])->name('frontend.home');
    Route::get('/cari-buku', [FrontendController::class, 'cariBuku'])->name('frontend.cari.buku');
    Route::get('/tentang', [FrontendController::class, 'tentang'])->name('frontend.tentang');
    Route::get('/koleksi-buku', [FrontendController::class, 'koleksiBuku'])->name('frontend.koleksi');
});

// API Routes untuk AJAX (tanpa middleware admin)
Route::middleware(['auth'])->group(function () {
    // Routes ini akan dipindah ke admin group
});



// Admin Routes (Admin dan Kepala Sekolah bisa akses)
Route::middleware(['auth', 'role:ADMIN,KEPALA_SEKOLAH'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/pengaturan-website', [AdminController::class, 'pengaturanWebsite'])->name('admin.pengaturan');
    Route::post('/pengaturan-website', [AdminController::class, 'updatePengaturanWebsite'])->name('admin.pengaturan.update');
    
    // Profil untuk semua role (Admin, Kepala Sekolah, Petugas)
    Route::get('/profil', [AdminController::class, 'profil'])->name('admin.profil');
    Route::post('/profil/update', [AdminController::class, 'updateProfil'])->name('admin.profil.update');
    Route::post('/profil/ganti-password', [AdminController::class, 'gantiPassword'])->name('admin.profil.ganti-password');
    Route::delete('/profil/hapus-foto', [AdminController::class, 'hapusFoto'])->name('admin.profil.hapus-foto');
    
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
    
    // Permission Management
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/role/{roleId}', [PermissionController::class, 'getRolePermissions'])->name('permissions.role.get');
    Route::post('/permissions/role/{roleId}/update', [PermissionController::class, 'updateRolePermissions'])->name('permissions.role.update');
    Route::post('/permissions/role/{roleId}/reset', [PermissionController::class, 'resetRolePermissions'])->name('permissions.role.reset');
    Route::post('/permissions/copy', [PermissionController::class, 'copyPermissions'])->name('permissions.copy');
    Route::post('/permissions/bulk-assign', [PermissionController::class, 'bulkAssignPermissions'])->name('permissions.bulk-assign');
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
    
    // Route pencarian anggota untuk pengembalian - dengan detail peminjaman
    // Route untuk pencarian anggota dengan peminjaman aktif - menggunakan controller method
    Route::get('/pengembalian/search-anggota', [PengembalianController::class, 'searchAnggota'])
        ->name('pengembalian.search-anggota');
    
    Route::get('/pengembalian/search-anggota-no-permission', [\App\Http\Controllers\PengembalianController::class, 'searchAnggotaNoPermission'])->name('pengembalian.search-anggota-no-permission');
    Route::get('/pengembalian/test-search-anggota', [\App\Http\Controllers\PengembalianController::class, 'testSearchAnggota'])->name('pengembalian.test-search-anggota');
    Route::get('/pengembalian/test-simple', function() {
        return response()->json([
            'success' => true,
            'message' => 'Route test simple berhasil',
            'user' => auth()->user() ? auth()->user()->name : 'Not logged in',
            'timestamp' => now()
        ]);
    })->name('pengembalian.test-simple');
    
    // Route untuk testing tanpa permission check
    // Route test yang sangat sederhana - tanpa middleware
    Route::get('/pengembalian/test-simple-search', function() {
        return response()->json([
            'success' => true,
            'message' => 'Route test berhasil',
            'timestamp' => now(),
            'user' => auth()->user() ? auth()->user()->name : 'Not logged in'
        ]);
    })->name('pengembalian.test-simple-search');
    

    
    Route::get('/pengembalian/search-anggota-test', function(Request $request) {
        $query = $request->get('query', '');
        $user = auth()->user();
        
        return response()->json([
            'success' => true,
            'message' => 'Route test search berhasil',
            'query' => $query,
            'user' => $user ? $user->name : 'Not logged in',
            'user_id' => $user ? $user->id : null,
            'is_admin' => $user ? $user->isAdmin() : false,
            'has_permission' => $user ? $user->hasPermission('pengembalian.manage') : false,
            'timestamp' => now()
        ]);
    })->name('pengembalian.search-anggota-test');
    Route::get('/pengembalian/get-peminjaman-aktif', [\App\Http\Controllers\PengembalianController::class, 'getPeminjamanAktif'])->name('pengembalian.get-peminjaman-aktif');
    Route::post('/pengembalian/scan-barcode', [\App\Http\Controllers\PengembalianController::class, 'scanBarcode'])->name('pengembalian.scan-barcode');
    Route::post('/pengembalian/scan-barcode-anggota', [\App\Http\Controllers\PengembalianController::class, 'scanBarcodeAnggota'])->name('pengembalian.scan-barcode-anggota');
    Route::get('/pengembalian/test-permission', [\App\Http\Controllers\PengembalianController::class, 'testPermission'])->name('pengembalian.test-permission');
    Route::post('/pengembalian/{id}/update-status-pembayaran-denda', [\App\Http\Controllers\PengembalianController::class, 'updateStatusPembayaranDenda'])->name('pengembalian.update-status-pembayaran-denda');
    Route::get('/pengembalian/{id}/denda-info', [\App\Http\Controllers\PengembalianController::class, 'getDendaInfo'])->name('pengembalian.denda-info');

    // Riwayat Pengembalian
    Route::get('/riwayat-pengembalian', [RiwayatPengembalianController::class, 'index'])->name('riwayat-pengembalian.index');
    Route::get('/riwayat-pengembalian/export', [RiwayatPengembalianController::class, 'export'])->name('riwayat-pengembalian.export');
    
    // CRUD Denda
    Route::resource('denda', DendaController::class)->names([
        'index' => 'admin.denda.index',
        'create' => 'admin.denda.create',
        'store' => 'admin.denda.store',
        'show' => 'admin.denda.show',
        'edit' => 'admin.denda.edit',
        'update' => 'admin.denda.update',
        'destroy' => 'admin.denda.destroy',
    ]);
    Route::post('/denda/hitung-denda', [DendaController::class, 'hitungDenda'])->name('admin.denda.hitung');
    Route::post('/denda/{id}/update-status', [DendaController::class, 'updateStatusPembayaran'])->name('admin.denda.update-status');
    Route::post('/denda/search', [DendaController::class, 'searchDenda'])->name('admin.denda.search');
    
    // CRUD Buku Tamu
    // Buku Tamu
    Route::resource('buku-tamu', BukuTamuController::class)->names([
        'index' => 'admin.buku-tamu.index',
        'create' => 'admin.buku-tamu.create',
        'store' => 'admin.buku-tamu.store',
        'show' => 'admin.buku-tamu.show',
        'edit' => 'admin.buku-tamu.edit',
        'update' => 'admin.buku-tamu.update',
        'destroy' => 'admin.buku-tamu.destroy',
    ]);
    Route::post('/buku-tamu/scan-barcode', [BukuTamuController::class, 'scanBarcode'])->name('admin.buku-tamu.scan-barcode');
    Route::get('/buku-tamu/search-members', [BukuTamuController::class, 'searchMembers'])->name('admin.buku-tamu.search-members');
    // Test route to debug the search-members issue
    Route::get('/buku-tamu/test-search-route', function() {
        return response()->json([
            'success' => true,
            'message' => 'Test route berhasil diakses',
            'user' => auth()->user() ? auth()->user()->name : 'Not logged in',
            'timestamp' => now(),
            'route_name' => 'admin.buku-tamu.test-search-route'
        ]);
    })->name('admin.buku-tamu.test-search-route');
    Route::post('/buku-tamu/store-ajax', [BukuTamuController::class, 'storeAjax'])->name('admin.buku-tamu.store-ajax');
    Route::post('/buku-tamu/record-exit', [BukuTamuController::class, 'recordExit'])->name('admin.buku-tamu.record-exit');
    Route::get('/buku-tamu/history', [BukuTamuController::class, 'history'])->name('admin.buku-tamu.history');
    Route::get('/buku-tamu/history/search', [BukuTamuController::class, 'searchHistory'])->name('admin.buku-tamu.history.search');
    Route::get('/buku-tamu/export-excel', [BukuTamuController::class, 'exportExcel'])->name('admin.buku-tamu.export-excel');
    Route::get('/buku-tamu/export-pdf', [BukuTamuController::class, 'exportPdf'])->name('admin.buku-tamu.export-pdf');
    Route::get('/buku-tamu/today', [BukuTamuController::class, 'todayVisitors'])->name('admin.buku-tamu.today');
    Route::get('/buku-tamu/create-test-data', [BukuTamuController::class, 'createTestData'])->name('admin.buku-tamu.create-test-data');
    Route::get('/buku-tamu/debug-data', [BukuTamuController::class, 'debugData'])->name('admin.buku-tamu.debug-data');
    
    // Riwayat Peminjaman
    Route::get('/riwayat-peminjaman', [RiwayatPeminjamanController::class, 'index'])->name('riwayat-peminjaman.index');
    Route::get('/riwayat-peminjaman/export', [RiwayatPeminjamanController::class, 'export'])->name('riwayat-peminjaman.export');
    

    
    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/anggota', [LaporanController::class, 'anggota'])->name('admin.laporan.anggota');
    Route::get('/laporan/buku', [LaporanController::class, 'buku'])->name('admin.laporan.buku');
    Route::get('/laporan/peminjaman', [LaporanController::class, 'peminjaman'])->name('admin.laporan.peminjaman');
    Route::get('/laporan/pengembalian', [LaporanController::class, 'pengembalian'])->name('admin.laporan.pengembalian');
    Route::get('/laporan/denda', [LaporanController::class, 'denda'])->name('admin.laporan.denda');
    Route::get('/laporan/absensi', [LaporanController::class, 'absensi'])->name('admin.laporan.absensi');
    Route::get('/laporan/kas', [LaporanController::class, 'kas'])->name('admin.laporan.kas');
    
    // Cetak
    Route::get('/cetak/label-buku/{id}', [CetakController::class, 'labelBuku'])->name('admin.cetak.label');
    Route::get('/cetak/kartu-anggota/{id}', [CetakController::class, 'kartuAnggota'])->name('admin.cetak.kartu');
});

// Petugas Routes
Route::middleware(['auth', 'role:PETUGAS'])->prefix('petugas')->group(function () {
    Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('petugas.dashboard');
    Route::get('/beranda', [PetugasController::class, 'beranda'])->name('petugas.beranda');
    Route::get('/tentang', [PetugasController::class, 'tentang'])->name('petugas.tentang');
    
    // Profil Petugas (menggunakan AdminController yang sudah ada)
    Route::get('/profil', [AdminController::class, 'profil'])->name('petugas.profil');
    Route::post('/profil/update', [AdminController::class, 'updateProfil'])->name('petugas.profil.update');
    Route::post('/profil/ganti-password', [AdminController::class, 'gantiPassword'])->name('petugas.profil.ganti-password');
    Route::delete('/profil/hapus-foto', [AdminController::class, 'hapusFoto'])->name('petugas.profil.hapus-foto');
    
    Route::resource('buku-tamu', BukuTamuController::class)->names([
        'index' => 'petugas.buku-tamu.index',
        'create' => 'petugas.buku-tamu.create',
        'store' => 'petugas.buku-tamu.store',
        'show' => 'petugas.buku-tamu.show',
        'edit' => 'petugas.buku-tamu.edit',
        'update' => 'petugas.buku-tamu.update',
        'destroy' => 'petugas.buku-tamu.destroy',
    ]);
    Route::post('/buku-tamu/scan-qr', [BukuTamuController::class, 'scanQR'])->name('petugas.buku-tamu.scan-qr');
    Route::get('/buku-tamu/search-members', [BukuTamuController::class, 'searchMembers'])->name('petugas.buku-tamu.search-members');
});

// Kepala Sekolah Routes
Route::middleware(['auth', 'role:KEPALA_SEKOLAH'])->prefix('kepsek')->group(function () {
    Route::get('/dashboard', [KepsekController::class, 'dashboard'])->name('kepsek.dashboard');
    Route::get('/laporan', [KepsekController::class, 'laporan'])->name('kepsek.laporan');
    
    // Profil Kepala Sekolah (menggunakan AdminController yang sudah ada)
    Route::get('/profil', [AdminController::class, 'profil'])->name('kepsek.profil');
    Route::post('/profil/update', [AdminController::class, 'updateProfil'])->name('kepsek.profil.update');
    Route::post('/profil/ganti-password', [AdminController::class, 'gantiPassword'])->name('kepsek.profil.ganti-password');
    Route::delete('/profil/hapus-foto', [AdminController::class, 'hapusFoto'])->name('kepsek.profil.hapus-foto');
    
    // Gunakan controller admin yang sudah ada
    Route::get('/riwayat-peminjaman', [RiwayatPeminjamanController::class, 'index'])->name('kepsek.riwayat-peminjaman');
    Route::get('/riwayat-pengembalian', [RiwayatPengembalianController::class, 'index'])->name('kepsek.riwayat-pengembalian');
    Route::get('/data-buku', [BukuController::class, 'index'])->name('kepsek.data-buku');
    Route::get('/data-anggota', [AnggotaController::class, 'index'])->name('kepsek.data-anggota');
    
    // Peminjaman aktif untuk kepala sekolah (moved to admin group)
    // Route::get('/peminjaman-aktif', [PeminjamanController::class, 'peminjamanAktif'])->name('kepsek.peminjaman-aktif');
    // Route::get('/peminjaman-aktif/export', [PeminjamanController::class, 'exportPeminjamanAktif'])->name('kepsek.peminjaman-aktif.export');
    
    // Export routes (gunakan yang sudah ada)
    Route::get('/export/riwayat-peminjaman', [RiwayatPeminjamanController::class, 'export'])->name('kepsek.export.riwayat-peminjaman');
    Route::get('/export/riwayat-pengembalian', [RiwayatPengembalianController::class, 'export'])->name('kepsek.export.riwayat-pengembalian');
    Route::get('/export/data-buku', [BukuController::class, 'export'])->name('kepsek.export.data-buku');
    Route::get('/export/data-anggota', [AnggotaController::class, 'export'])->name('kepsek.export.data-anggota');
});

// Test route untuk debugging permission
Route::get('/test-permissions', function () {
    $user = auth()->user();
    if (!$user) {
        return 'Not logged in';
    }
    
    echo "<h2>Testing permissions for: {$user->nama_lengkap}</h2>";
    echo "<p>Role: {$user->role->nama_peran} ({$user->role->kode_peran})</p>";
    
    echo "<h3>Peminjaman Permissions:</h3>";
    echo "peminjaman.view: " . ($user->hasPermission('peminjaman.view') ? 'YES' : 'NO') . "<br>";
    echo "peminjaman.create: " . ($user->hasPermission('peminjaman.create') ? 'YES' : 'NO') . "<br>";
    echo "peminjaman.edit: " . ($user->hasPermission('peminjaman.edit') ? 'YES' : 'NO') . "<br>";
    echo "peminjaman.delete: " . ($user->hasPermission('peminjaman.delete') ? 'YES' : 'NO') . "<br>";
    echo "peminjaman.show: " . ($user->hasPermission('peminjaman.show') ? 'YES' : 'NO') . "<br>";
    
    echo "<h3>Pengembalian Permissions:</h3>";
    echo "pengembalian.view: " . ($user->hasPermission('pengembalian.view') ? 'YES' : 'NO') . "<br>";
    echo "pengembalian.create: " . ($user->hasPermission('pengembalian.create') ? 'YES' : 'NO') . "<br>";
    echo "pengembalian.edit: " . ($user->hasPermission('pengembalian.edit') ? 'YES' : 'NO') . "<br>";
    echo "pengembalian.delete: " . ($user->hasPermission('pengembalian.delete') ? 'YES' : 'NO') . "<br>";
    echo "pengembalian.show: " . ($user->hasPermission('pengembalian.show') ? 'YES' : 'NO') . "<br>";
    
    echo "<h3>All Permissions:</h3>";
    $permissions = $user->getAllPermissions();
    foreach ($permissions as $permission) {
        echo "- {$permission->name} ({$permission->slug})<br>";
    }
    
    echo "<h3>Test Links:</h3>";
    echo "<a href='/admin/peminjaman'>Go to Peminjaman</a><br>";
    echo "<a href='/admin/pengembalian'>Go to Pengembalian</a><br>";
    
    return '';
})->middleware('auth');

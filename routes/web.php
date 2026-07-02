<?php

use App\Http\Controllers\PasienController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Landing page - redirect jika sudah login, langsung ke login jika belum
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return $user->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('pasien.dashboard');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
});

// Routes Pasien
Route::middleware(['auth'])->prefix('pasien')->name('pasien.')->group(function () {
    Route::get('/dashboard', [PasienController::class, 'dashboard'])->name('dashboard');
    Route::get('/dokter', [PasienController::class, 'dokter'])->name('dokter');
    Route::get('/daftar/{dokter}', [PasienController::class, 'daftarForm'])->name('daftar');
    Route::post('/daftar/{dokter}', [PasienController::class, 'daftarStore'])->name('daftar.store');
    Route::get('/riwayat', [PasienController::class, 'riwayat'])->name('riwayat');
    Route::patch('/batal/{pendaftaran}', [PasienController::class, 'batal'])->name('batal');
});

require __DIR__ . '/auth.php';

// Routes Admin
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');

    // Pendaftaran / Antrian
    Route::get('/antrian', [\App\Http\Controllers\AdminController::class, 'antrian'])->name('antrian');
    // alias untuk layout yang menautkan ke admin.pendaftaran.index
    Route::get('/pendaftaran', [\App\Http\Controllers\AdminController::class, 'antrian'])->name('pendaftaran.index');
    Route::match(['post', 'patch'], '/antrian/{pendaftaran}/status', [\App\Http\Controllers\AdminController::class, 'updateStatus'])->name('antrian.status');

    // Kelola dokter
    Route::get('/dokter', [\App\Http\Controllers\AdminController::class, 'dokterIndex'])->name('dokter.index');
    Route::get('/dokter/create', [\App\Http\Controllers\AdminController::class, 'dokterCreate'])->name('dokter.create');
    Route::post('/dokter', [\App\Http\Controllers\AdminController::class, 'dokterStore'])->name('dokter.store');
    Route::get('/dokter/{dokter}/edit', [\App\Http\Controllers\AdminController::class, 'dokterEdit'])->name('dokter.edit');
    Route::match(['put', 'patch'], '/dokter/{dokter}', [\App\Http\Controllers\AdminController::class, 'dokterUpdate'])->name('dokter.update');
    Route::delete('/dokter/{dokter}', [\App\Http\Controllers\AdminController::class, 'dokterDestroy'])->name('dokter.destroy');

    // Kelola jadwal
    Route::get('/jadwal', [\App\Http\Controllers\AdminController::class, 'jadwalIndex'])->name('jadwal.index');
    Route::get('/jadwal/create', [\App\Http\Controllers\AdminController::class, 'jadwalCreate'])->name('jadwal.create');
    Route::post('/jadwal', [\App\Http\Controllers\AdminController::class, 'jadwalStore'])->name('jadwal.store');
    Route::get('/jadwal/{jadwal}/edit', [\App\Http\Controllers\AdminController::class, 'jadwalEdit'])->name('jadwal.edit');
    Route::match(['put', 'patch'], '/jadwal/{jadwal}', [\App\Http\Controllers\AdminController::class, 'jadwalUpdate'])->name('jadwal.update');
    Route::delete('/jadwal/{jadwal}', [\App\Http\Controllers\AdminController::class, 'jadwalDestroy'])->name('jadwal.destroy');
    Route::post('/jadwal/{jadwal}/toggle', [\App\Http\Controllers\AdminController::class, 'jadwalToggle'])->name('jadwal.toggle');
});

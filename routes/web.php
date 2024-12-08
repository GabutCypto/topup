<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\OflineController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->group(function(){
        Route::resource('toko', TokoController::class)->middleware('role:owner');
        Route::resource('kategori', KategoriController::class)->middleware('role:owner');
        Route::resource('produk', ProdukController::class)->middleware('role:owner');
    });


    Route::middleware(['auth'])->group(function () {
        Route::get('/user/saldo', [SaldoController::class, 'index'])->name('user.saldo.index');
        Route::get('/user/saldo/create', [SaldoController::class, 'create'])->name('user.saldo.create');
        Route::post('/user/saldo', [SaldoController::class, 'store'])->name('user.saldo.store');
        Route::put('/user/saldo/{saldo}', [SaldoController::class, 'update'])->name('user.saldo.update');
        Route::get('/user/saldo/{saldo}', [SaldoController::class, 'show'])->name('user.saldo.show');
    });

    Route::middleware('role:owner|buyer')->group(function() {
        Route::get('/admin/oflines', [OflineController::class, 'index'])->name('ofline.index');
    });
    
    Route::middleware('role:owner')->group(function() {
        Route::get('/admin/oflines/create', [OflineController::class, 'create'])->name('ofline.create');
        Route::post('/admin/oflines', [OflineController::class, 'store'])->name('ofline.store');
    });


});


require __DIR__.'/auth.php';
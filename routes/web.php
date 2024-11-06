<?php
use App\Http\Controllers\AntrianController;

// Halaman utama untuk melihat daftar antrian dan tombol untuk menambah antrian baru
Route::get('/antrian', [AntrianController::class, 'index'])->name('antrian.index');

// Menampilkan halaman pemilihan jenis pasien (dijalankan setelah klik "Tambah Antrian Baru")
Route::post('/antrian/pilih-jenis-pasien', [AntrianController::class, 'pilihJenisPasien'])->name('antrian.jenis_pasien');

// Menampilkan halaman pemilihan metode pembayaran setelah jenis pasien dipilih
Route::post('/antrian/pilih-metode-pembayaran', [AntrianController::class, 'pilihMetodePembayaran'])->name('antrian.jenis_pembayaran');
Route::post('/antrian/store', [AntrianController::class, 'storepilihMetodePembayaran'])->name('antrian.store_jenis_pembayaran');
Route::get('/antrian/result/{queue_number}/{current_number}', [AntrianController::class, 'result'])->name('antrian.result');

// Menghapus semua nomor antrian dari cookie
Route::get('/antrian/reset', [AntrianController::class, 'reset'])->name('antrian.reset');
Route::get('/antrian/daftar', [AntrianController::class, 'daftarAntrian'])->name('antrian.daftar');

Route::get('/antrian/delete_pendaftaran/{queue_number_delete}', [AntrianController::class, 'delete_pendaftaran']);


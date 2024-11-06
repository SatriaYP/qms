<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AntrianController extends Controller
{
    public function index()
    {
        // Ambil daftar nomor antrian dari cookie jika ada, atau set sebagai array kosong
        $queue_numbers = json_decode(Cookie::get('queue_numbers', '[]'), true);
        return view('antrian.pilih_jenis_pasien', compact('queue_numbers'));
    }

    public function pilihJenisPasien(Request $request)
    {
        $jenis_pasien = $request->jenis_pasien;
        // Simpan pilihan jenis pasien dalam session
        $request->session()->put('jenis_pasien', $request->input('jenis_pasien'));
        return view('antrian.pilih_metode_pembayaran', compact('jenis_pasien'));
    }
    public function pilihMetodePembayaran(Request $request)
    {
        $jenis_pasien = $request->jenis_pasien;
        return view('antrian.pilih_metode_pembayaran', compact('jenis_pasien'));
    }

    public function storepilihMetodePembayaran(Request $request)
    {
        // Ambil jenis pasien dari session dan metode pembayaran dari request
        $jenis_pasien = $request->jenis_pasien; // 'A' untuk baru, 'B' untuk lama
        $metode_pembayaran = $request->input('metode_pembayaran'); // '0' untuk Asuransi, '1' untuk BPJS, '2' untuk Mandiri

        // Ambil nomor antrian terakhir dari cookie atau mulai dari 1 jika tidak ada
        $current_number = Cookie::get('last_queue_number', 1);

        // Format nomor antrian: A1001
        $queue_number = $jenis_pasien . $metode_pembayaran . str_pad($current_number, 3, '0', STR_PAD_LEFT);
        // return view('antrian.result', compact('queue_number'));
        return redirect()->route('antrian.result', ['queue_number' => $queue_number, 'current_number' => $current_number]);
    }

    public function reset()
    {
        // Hapus semua nomor antrian dari cookie
        $queue_numbers_cookie = Cookie::forget('queue_numbers');
        $last_number_cookie = Cookie::forget('last_queue_number');
        return redirect('/antrian')->withCookie($queue_numbers_cookie)->withCookie($last_number_cookie);
    }
    public function result($queue_number, $current_number)
    {
        $qr_code = QrCode::generate($queue_number);

        // Ambil array nomor antrian dari cookie dan tambahkan nomor baru
        $queue_numbers = json_decode(Cookie::get('queue_numbers', '[]'), true);
        $queue_numbers[] = $queue_number;

        // Simpan array nomor antrian yang diperbarui dan nomor antrian terakhir dalam cookie
        $queue_numbers_cookie = cookie('queue_numbers', json_encode($queue_numbers), 1440);
        $last_number_cookie = cookie('last_queue_number', $current_number + 1, 1440);

        // Tampilkan halaman hasil dengan nomor antrian dan QR code
        return response()
            ->view('antrian.result', compact('queue_number', 'qr_code'))
            ->cookie($queue_numbers_cookie)
            ->cookie($last_number_cookie);
    }
    public function daftarAntrian()
    {
        // Ambil daftar nomor antrian dari cookie
        $queue_numbers = json_decode(Cookie::get('queue_numbers', '[]'), true);
        // dd($queue    _numbers[]);
        return view('antrian.daftar', compact('queue_numbers'));
    }
    public function delete_pendaftaran($queue_number_delete)
{
    $queue_numbers = json_decode(Cookie::get('queue_numbers', '[]'), true);
    // Ambil semua nomor antrian yang ada dalam cookie
    // Cek apakah queue_number_delete ada di dalam daftar queue_numbers
    if (in_array($queue_number_delete, $queue_numbers)) {
        // Hapus queue_number_delete dari daftar
        $queue_numbers = array_values(array_diff($queue_numbers, [$queue_number_delete]));

        // Update cookie dengan daftar queue_numbers terbaru
        Cookie::queue('queue_numbers', json_encode($queue_numbers), 1440);

        // Kembalikan pesan sukses dalam format JSON
        return response()->json([
            'success' => true,
            'message' => "Nomor antrian $queue_number_delete berhasil dihapus",
            'data' => $queue_numbers
        ]);
    } else {
        // Jika queue_number_delete tidak ditemukan, kembalikan pesan error
        return response()->json([
            'success' => false,
            'message' => "Nomor antrian $queue_number_delete tidak ditemukan",
            'data' => $queue_numbers
        ]);
    }
}


}

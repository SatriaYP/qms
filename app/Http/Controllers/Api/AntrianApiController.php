<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use App\Http\Resources\AntrianResource;

class AntrianApiController extends Controller
{
    // Fungsi untuk menampilkan daftar atau menghapus queue_number
    public function index(Request $request)
    {
        // Ambil semua nomor antrian yang ada dalam cookie
        $queue_numbers = json_decode(Cookie::get('queue_numbers', '[]'), true);

        // Cek apakah ada queue_number yang dikirim dari request
        if ($request->has('queue_number')) {
            $queue_number = $request->input('queue_number');

            // Hapus queue_number yang dikirimkan dari daftar queue_numbers jika ada
            if (($key = array_search($queue_number, $queue_numbers)) !== false) {
                unset($queue_numbers[$key]);

                // Update cookie dengan daftar queue_numbers terbaru
                Cookie::queue('queue_numbers', json_encode(array_values($queue_numbers)), 1440);

                // Kembalikan respon sukses setelah penghapusan
                return new AntrianResource(true, "Nomor antrian $queue_number berhasil dihapus", array_values($queue_numbers));
            } else {
                // Jika queue_number tidak ditemukan, kembalikan pesan error
                return new AntrianResource(false, "Nomor antrian $queue_number tidak ditemukan", $queue_numbers);
            }
        }

        // Jika tidak ada queue_number yang dikirimkan, tampilkan daftar nomor antrian
        return new AntrianResource(true, 'Daftar nomor antrian', $queue_numbers);
    }
}

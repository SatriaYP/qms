<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    use HasFactory;

    protected $table = 'antrians';

    public static function getNextQueueNumber()
    {
        // Ambil nomor antrian terakhir, atau mulai dari 1 jika belum ada
        $last = self::orderBy('created_at', 'desc')->first();
        return $last ? $last->queue_number + 1 : 1;
    }
}

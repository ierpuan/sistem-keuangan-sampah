<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPembayaran extends Model
{
    use HasFactory;

    protected $table = 'transaksi_pembayaran';
    protected $primaryKey = 'id_transaksi';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_tagihan',
        'id_pengguna',
        'tgl_bayar',
        'jml_bayar_input',
    ];

    protected $casts = [
        'jml_bayar_input' => 'decimal:2',
        'tgl_bayar' => 'datetime',
    ];

    // Relationships
    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'id_tagihan', 'id_tagihan');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    // Event setelah pembayaran dibuat
    // protected static function booted()
    // {
    //     static::created(function ($transaksi) {
    //         // Update total sudah bayar di tagihan
    //         $tagihan = $transaksi->tagihan;
    //         $tagihan->total_sudah_bayar += $transaksi->jml_bayar_input;
    //         $tagihan->save();

    //         // Update status tagihan
    //         $tagihan->updateStatus();
    //     });
    // }
}
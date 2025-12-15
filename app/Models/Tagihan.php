<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $table = 'tagihan';
    protected $primaryKey = 'id_tagihan';
    public $timestamps = true;

    protected $fillable = [
        'id_pelanggan',
        'periode',
        'jml_tagihan_pokok',
        'jatuh_tempo',
        'total_sudah_bayar',
        'status',
    ];

    protected $casts = [
        'jml_tagihan_pokok' => 'decimal:2',
        'total_sudah_bayar' => 'decimal:2',
        'jatuh_tempo' => 'date',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function transaksiPembayaran()
    {
        return $this->hasMany(TransaksiPembayaran::class, 'id_tagihan', 'id_tagihan');
    }

    // Accessor untuk sisa tagihan
    public function getSisaTagihanAttribute()
    {
        return $this->jml_tagihan_pokok - $this->total_sudah_bayar;
    }

    // Accessor untuk status lunas
    public function getIsLunasAttribute()
    {
        return $this->status === 'Lunas';
    }

    // Scope untuk filter berdasarkan status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope untuk tagihan belum lunas
    public function scopeBelumLunas($query)
    {
        return $query->whereIn('status', ['Belum Bayar', 'Tunggakan']);
    }

    // Scope untuk filter berdasarkan periode
    public function scopePeriode($query, $periode)
    {
        return $query->where('periode', $periode);
    }

    // Update status tagihan
    public function updateStatus()
{
    // Jika sudah lunas
    if ($this->total_sudah_bayar >= $this->jml_tagihan_pokok) {
        $this->status = 'Lunas';
    }
    // Jika bayar sebagian (kurang dari tagihan) → Tunggakan
    elseif ($this->total_sudah_bayar > 0 && $this->total_sudah_bayar < $this->jml_tagihan_pokok) {
        $this->status = 'Tunggakan';
    }
    // Jika belum bayar sama sekali dan sudah lewat jatuh tempo
    elseif ($this->jatuh_tempo < now()) {
        $this->status = 'Tunggakan';
    }
    // Selain itu
    else {
        $this->status = 'Belum Bayar';
    }

    $this->save();
}

}
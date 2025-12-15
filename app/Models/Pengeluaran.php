<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';
    protected $primaryKey = 'id_pengeluaran';
    public $timestamps = true;

    protected $fillable = [
        'id_pengguna',
        'tanggal_pengeluaran',
        'jumlah',
        'kategori',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_pengeluaran' => 'date',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    // Scope untuk filter berdasarkan kategori
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    // Scope untuk filter berdasarkan tanggal
    public function scopeTanggalBetween($query, $start, $end)
    {
        return $query->whereBetween('tanggal_pengeluaran', [$start, $end]);
    }

    // Scope untuk bulan tertentu
    public function scopeBulan($query, $bulan, $tahun)
    {
        return $query->whereYear('tanggal_pengeluaran', $tahun)
                     ->whereMonth('tanggal_pengeluaran', $bulan);
    }
}
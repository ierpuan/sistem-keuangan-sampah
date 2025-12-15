<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';
    public $timestamps = true;

    protected $fillable = [
        'nama',
        'dusun',
        'rt',
        'rw',
        'alamat',
        'status_aktif',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function deposit()
    {
        return $this->hasOne(DepositPelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Accessor untuk alamat lengkap
    public function getAlamatLengkapAttribute()
    {
        $parts = array_filter([
            $this->alamat,
            $this->rt ? "RT {$this->rt}" : null,
            $this->rw ? "RW {$this->rw}" : null,
            $this->dusun ? "Dusun {$this->dusun}" : null,
        ]);

        return implode(', ', $parts);
    }

    public function scopeCariRTRW($query, $rt = null, $rw = null)
    {
        if (!is_null($rt) && $rt !== '') {
            $query->where('rt', $rt);
        }
        if (!is_null($rw) && $rw !== '') {
            $query->where('rw', $rw);
        }

        return $query;
    }

    // Scope untuk filter pelanggan aktif
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', 'Aktif');
    }

    // Scope untuk filter per dusun
    public function scopeDusun($query, $dusun)
    {
        return $query->where('dusun', $dusun);
    }
}
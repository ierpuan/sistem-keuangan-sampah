<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositPelanggan extends Model
{
    use HasFactory;

    protected $table = 'deposit_pelanggan';
    protected $primaryKey = 'id_deposit';
    public $timestamps = false;

    protected $fillable = [
        'id_pelanggan',
        'saldo_deposit',
    ];

    protected $casts = [
        'saldo_deposit' => 'decimal:2',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Method untuk tambah saldo
    public function tambahSaldo($jumlah)
    {
        $this->saldo_deposit += $jumlah;
        $this->save();
    }

    // Method untuk kurangi saldo
    public function kurangiSaldo($jumlah)
    {
        if ($this->saldo_deposit >= $jumlah) {
            $this->saldo_deposit -= $jumlah;
            $this->save();
            return true;
        }
        return false;
    }

    // Check apakah saldo cukup
    public function cukupSaldo($jumlah)
    {
        return $this->saldo_deposit >= $jumlah;
    }
}
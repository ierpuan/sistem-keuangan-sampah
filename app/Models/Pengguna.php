<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';
    public $timestamps = true;

    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // TAMBAHKAN METHOD INI untuk custom primary key
    public function getAuthIdentifierName()
    {
        return 'id_pengguna';
    }

    // Relationships
    public function transaksiPembayaran()
    {
        return $this->hasMany(TransaksiPembayaran::class, 'id_pengguna', 'id_pengguna');
    }

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'id_pengguna', 'id_pengguna');
    }
}
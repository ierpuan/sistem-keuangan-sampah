<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class LokasiPelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::where('status_aktif', 'Aktif')
                         ->whereNotNull('latitude')
                         ->whereNotNull('longitude');

    if ($request->filled('search')) {
        $search = $request->search;
           $query->where(function($q) use ($search) {
           $q->where('nama', 'like', '%' . $search . '%')
           ->orWhere('alamat', 'like', '%' . $search . '%');
    });
    }
        // Filter berdasarkan dusun
        if ($request->filled('dusun')) {
            $query->where('dusun', $request->dusun);
        }

        // Filter berdasarkan RT
        if ($request->filled('rt')) {
            $query->where('rt', $request->rt);
        }

        // Filter berdasarkan RW
        if ($request->filled('rw')) {
            $query->where('rw', $request->rw);
        }

        $pelanggan = $query->get();

        // Get unique values untuk filter dropdown

        $dusun_list = Pelanggan::where('status_aktif', 'Aktif')
                              ->whereNotNull('dusun')
                              ->distinct()
                              ->pluck('dusun')
                              ->sort();

        $rt_list = Pelanggan::where('status_aktif', 'Aktif')
                           ->whereNotNull('rt')
                           ->distinct()
                           ->pluck('rt')
                           ->sort();

        $rw_list = Pelanggan::where('status_aktif', 'Aktif')
                           ->whereNotNull('rw')
                           ->distinct()
                           ->pluck('rw')
                           ->sort();

        // Convert pelanggan ke JSON untuk JavaScript
        $pelangganJson = $pelanggan->map(function($p) {
            return [
                'id' => $p->id_pelanggan,
                'nama' => $p->nama,
                'alamat' => $p->alamat_lengkap,
                'dusun' => $p->dusun,
                'rt' => $p->rt,
                'rw' => $p->rw,
                'latitude' => (float) $p->latitude,
                'longitude' => (float) $p->longitude,
                'status' => $p->status_aktif,
            ];
        });

        return view('lokasi.index', compact('pelanggan', 'pelangganJson', 'dusun_list', 'rt_list', 'rw_list'));
    }
}
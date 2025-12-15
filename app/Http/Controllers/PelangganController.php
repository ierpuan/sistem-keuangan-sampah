<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\DepositPelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::query();

        // Filter berdasarkan RT
        if ($request->filled('rt')) {
            $query->where('rt', $request->rt);
        }

        // Filter berdasarkan RW
        if ($request->filled('rw')) {
            $query->where('rw', $request->rw);
        }

        // Filter berdasarkan dusun
        if ($request->filled('dusun')) {
            $query->where('dusun', $request->dusun);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('alamat', 'like', '%' . $request->search . '%');
            });
        }

        $pelanggan = $query->with('deposit')->paginate(20);
        $dusun_list = Pelanggan::distinct()->pluck('dusun');
        $rt_list = Pelanggan::distinct()->pluck('rt');
        $rw_list = Pelanggan::distinct()->pluck('rw');

        return view('pelanggan.index', compact('pelanggan', 'dusun_list', 'rt_list', 'rw_list'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|max:100',
            'dusun' => 'nullable|max:50',
            'rt' => 'nullable|regex:/^\d{3}$/',
            'rw' => 'nullable|regex:/^\d{3}$/',
            'alamat' => 'nullable',
            'status_aktif' => 'required|in:Aktif,Nonaktif',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $pelanggan = Pelanggan::create($validated);

        // Buat deposit default
        DepositPelanggan::create([
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'saldo_deposit' => 0,
        ]);

        return redirect()->route('pelanggan.index')
                        ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function show(Pelanggan $pelanggan)
    {
        $pelanggan->load(['tagihan' => function($query) {
            $query->orderBy('periode', 'desc');
        }, 'deposit']);

        return view('pelanggan.show', compact('pelanggan'));
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validated = $request->validate([
            'nama' => 'required|max:100',
            'dusun' => 'nullable|max:50',
            'rt' => 'nullable|regex:/^\d{3}$/',
             'rw' => 'nullable|regex:/^\d{3}$/',
            'alamat' => 'nullable',
            'status_aktif' => 'required|in:Aktif,Nonaktif',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $pelanggan->update($validated);

        return redirect()->route('pelanggan.index')
                        ->with('success', 'Data pelanggan berhasil diupdate.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')
                        ->with('success', 'Pelanggan berhasil dihapus.');
    }
}
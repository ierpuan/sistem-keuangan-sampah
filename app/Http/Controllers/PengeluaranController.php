<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengeluaran::with('pengguna');

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter berdasarkan tanggal - PERBAIKAN DI SINI
        // Nama parameter harus sesuai dengan yang ada di view: dari_tanggal dan sampai_tanggal
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal_pengeluaran', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal_pengeluaran', '<=', $request->sampai_tanggal);
        }

        $pengeluaran = $query->orderBy('tanggal_pengeluaran', 'desc')->paginate(15);

        // PENTING: Total harus dihitung dengan query terpisah karena pagination
        $totalQuery = Pengeluaran::query();

        if ($request->filled('kategori')) {
            $totalQuery->where('kategori', $request->kategori);
        }

        if ($request->filled('dari_tanggal')) {
            $totalQuery->whereDate('tanggal_pengeluaran', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $totalQuery->whereDate('tanggal_pengeluaran', '<=', $request->sampai_tanggal);
        }

        $totalPengeluaran = $totalQuery->sum('jumlah');

        $kategoriList = Pengeluaran::distinct()->pluck('kategori');

        return view('pengeluaran.index', compact('pengeluaran', 'totalPengeluaran', 'kategoriList'));
    }

    public function create()
    {
        return view('pengeluaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_pengeluaran' => 'required|date',
            'jumlah' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    // Cek jika ada tanda titik atau koma
                    if ($value !== null && (strpos((string)$value, '.') !== false || strpos((string)$value, ',') !== false)) {
                        $fail('Jumlah pengeluaran tidak boleh menggunakan tanda titik atau koma. Masukkan angka bulat saja (misal: 10000).');
                    }
                }
            ],
            'kategori' => 'required|max:50',
            'keterangan' => 'nullable',
        ]);

        Pengeluaran::create([
            ...$validated,
            'id_pengguna' => Auth::id(),
        ]);

        return redirect()->route('pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    public function show(Pengeluaran $pengeluaran)
    {
        return view('pengeluaran.show', compact('pengeluaran'));
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        return view('pengeluaran.edit', compact('pengeluaran'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $request->validate([
            'tanggal_pengeluaran' => 'required|date',
            'jumlah' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    // Cek jika ada tanda titik atau koma
                    if ($value !== null && (strpos((string)$value, '.') !== false || strpos((string)$value, ',') !== false)) {
                        $fail('Jumlah pengeluaran tidak boleh menggunakan tanda titik atau koma. Masukkan angka bulat saja (misal: 10000).');
                    }
                }
            ],
            'kategori' => 'required|max:50',
            'keterangan' => 'nullable',
        ]);

        $pengeluaran->update($validated);

        return redirect()->route('pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil diupdate!');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();

        return redirect()->route('pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil dihapus!');
    }
}
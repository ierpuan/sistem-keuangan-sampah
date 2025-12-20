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
            $query->kategori($request->kategori);
        }

        // Filter berdasarkan periode
        if ($request->filled('periode')) {
            switch ($request->periode) {
                case 'hari_ini':
                    $query->whereDate('tanggal_pengeluaran', today());
                    break;

                case 'minggu_ini':
                    $query->whereBetween('tanggal_pengeluaran', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]);
                    break;

                case 'bulan_ini':
                    $query->whereMonth('tanggal_pengeluaran', now()->month)
                          ->whereYear('tanggal_pengeluaran', now()->year);
                    break;

                case 'bulan_lalu':
                    $query->whereMonth('tanggal_pengeluaran', now()->subMonth()->month)
                          ->whereYear('tanggal_pengeluaran', now()->subMonth()->year);
                    break;

                case '3_bulan':
                    $query->where('tanggal_pengeluaran', '>=', now()->subMonths(3));
                    break;

                case 'tahun_ini':
                    $query->whereYear('tanggal_pengeluaran', now()->year);
                    break;
            }
        }

        // Filter berdasarkan tanggal (fallback jika masih ada)
        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->tanggalBetween($request->tanggal_dari, $request->tanggal_sampai);
        }

        $pengeluaran = $query->orderBy('tanggal_pengeluaran', 'desc')->paginate(15);

        // Total pengeluaran sesuai filter yang aktif
        $totalPengeluaran = $query->sum('jumlah');

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
            'jumlah' => 'required|numeric|min:0',
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
            'jumlah' => 'required|numeric|min:0',
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
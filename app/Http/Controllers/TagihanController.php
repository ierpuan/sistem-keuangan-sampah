<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        $query = Tagihan::with('pelanggan');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter periode
        if ($request->filled('periode')) {
            $query->where('periode', $request->periode);
        }

        // Filter pelanggan
        if ($request->filled('pelanggan')) {
            $query->whereHas('pelanggan', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->pelanggan . '%');
            });
        }

        $tagihan = $query->orderBy('periode', 'desc')
                        ->orderBy('id_pelanggan', 'asc')
                        ->paginate(20);

        $pelanggan_list = Pelanggan::aktif()->get();
        $periode_list = Tagihan::distinct()->pluck('periode')->sort()->reverse();

        return view('tagihan.index', compact('tagihan', 'pelanggan_list', 'periode_list'));
    }

    public function create()
    {
        $pelanggan = Pelanggan::aktif()->get();
        return view('tagihan.create', compact('pelanggan'));
    }
    public function cetakPeriode(Request $request)
{
    $request->validate([
        'periode' => 'required'
    ]);

    $tagihan = Tagihan::with('pelanggan')
        ->where('periode', $request->periode)
        ->orderBy('id_pelanggan')
        ->get();

    return view('tagihan.cetak-semua', compact('tagihan'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'periode' => 'required|size:7|regex:/^\d{4}-\d{2}$/',
            'jml_tagihan_pokok' => 'required|numeric|min:0',
            'jatuh_tempo' => 'required|date',
        ]);

        // Cek duplikasi
        $exists = Tagihan::where('id_pelanggan', $validated['id_pelanggan'])
                        ->where('periode', $validated['periode'])
                        ->exists();

        if ($exists) {
            return back()->withErrors(['periode' => 'Tagihan untuk periode ini sudah ada.'])
                        ->withInput();
        }

        Tagihan::create($validated);

        return redirect()->route('tagihan.index')
                        ->with('success', 'Tagihan berhasil ditambahkan.');
    }

    public function generateBulk(Request $request)
    {
        $validated = $request->validate([
            'periode' => 'required|size:7|regex:/^\d{4}-\d{2}$/',
            'jml_tagihan_pokok' => 'required|numeric|min:0',
        ]);

        $pelanggan = Pelanggan::aktif()->get();
        $periode = $validated['periode'];
        $jatuh_tempo = Carbon::createFromFormat('Y-m', $periode)->endOfMonth();

        $created = 0;
        foreach ($pelanggan as $p) {
            // Cek apakah sudah ada
            $exists = Tagihan::where('id_pelanggan', $p->id_pelanggan)
                            ->where('periode', $periode)
                            ->exists();

            if (!$exists) {
                Tagihan::create([
                    'id_pelanggan' => $p->id_pelanggan,
                    'periode' => $periode,
                    'jml_tagihan_pokok' => $validated['jml_tagihan_pokok'],
                    'jatuh_tempo' => $jatuh_tempo,
                ]);
                $created++;
            }
        }

        return redirect()->route('tagihan.index')
                        ->with('success', "Berhasil generate {$created} tagihan untuk periode {$periode}.");
    }

    public function show(Tagihan $tagihan)
    {
        $tagihan->load(['pelanggan', 'transaksiPembayaran.pengguna']);
        return view('tagihan.show', compact('tagihan'));
    }

    public function edit(Tagihan $tagihan)
    {
        // Cegah edit jika sudah ada pembayaran
        if ($tagihan->transaksiPembayaran()->exists()) {
            return redirect()
                ->route('tagihan.show', $tagihan->id_tagihan)
                ->with('error', 'Tagihan tidak dapat diedit karena sudah memiliki pembayaran.');
        }

        $tagihan->load('pelanggan');

        return view('tagihan.edit', compact('tagihan'));
    }
    public function update(Request $request, Tagihan $tagihan)
    {
        // Cegah update jika sudah ada pembayaran
        if ($tagihan->transaksiPembayaran()->exists()) {
            return redirect()
                ->route('tagihan.show', $tagihan->id_tagihan)
                ->with('error', 'Tagihan tidak dapat diubah karena sudah memiliki pembayaran.');
        }

        $validated = $request->validate([
            'jml_tagihan_pokok' => 'required|numeric|min:0',
            'jatuh_tempo'       => 'required|date',
        ]);

        $tagihan->update($validated);

        // Update status setelah perubahan
        $tagihan->updateStatus();

        return redirect()
            ->route('tagihan.show', $tagihan->id_tagihan)
            ->with('success', 'Tagihan berhasil diperbarui.');
    }

    public function destroy(Tagihan $tagihan)
    {
        if ($tagihan->transaksiPembayaran()->count() > 0) {
            return back()->withErrors(['error' => 'Tagihan tidak bisa dihapus karena sudah ada pembayaran.']);
        }

        $tagihan->delete();

        return redirect()->route('tagihan.index')
                        ->with('success', 'Tagihan berhasil dihapus.');
    }
}
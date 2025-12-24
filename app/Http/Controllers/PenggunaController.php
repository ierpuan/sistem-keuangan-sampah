<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pengguna::query();

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        $pengguna = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('pengguna.index', compact('pengguna'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pengguna.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:pengguna,username',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:Admin,Petugas',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        Pengguna::create($validated);

        return redirect()->route('pengguna.index')
                        ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengguna $pengguna)
    {
        // Load relasi untuk statistik
        $pengguna->load(['transaksiPembayaran', 'pengeluaran']);

        // Hitung statistik
        $stats = [
            'total_transaksi' => $pengguna->transaksiPembayaran()->count(),
            'total_pembayaran' => $pengguna->transaksiPembayaran()->sum('jml_bayar_input'),
            'total_pengeluaran_dicatat' => $pengguna->pengeluaran()->count(),
            'total_nilai_pengeluaran' => $pengguna->pengeluaran()->sum('jumlah'),
        ];

        // Transaksi terbaru
        $transaksi_terbaru = $pengguna->transaksiPembayaran()
                                     ->with('tagihan.pelanggan')
                                     ->orderBy('tgl_bayar', 'desc')
                                     ->limit(10)
                                     ->get();

        return view('pengguna.show', compact('pengguna', 'stats', 'transaksi_terbaru'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengguna $pengguna)
    {
        return view('pengguna.edit', compact('pengguna'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengguna $pengguna)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('pengguna', 'username')->ignore($pengguna->id_pengguna, 'id_pengguna')
            ],
            'current_password' => 'required_with:password|string',
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:Admin,Petugas',
        ], [
            'current_password.required_with' => 'Password lama harus diisi jika ingin mengganti password.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
        ]);

        // Jika password diisi, verifikasi password lama dan hash password baru
        if ($request->filled('password')) {
            // Verifikasi password lama
            if (!Hash::check($request->current_password, $pengguna->password)) {
                return back()->withErrors([
                    'current_password' => 'Password lama tidak sesuai.'
                ])->withInput();
            }

            $validated['password'] = Hash::make($validated['password']);
        } else {
            // Jika tidak, hapus dari array agar tidak diupdate
            unset($validated['password']);
        }

        // Hapus current_password dari array update
        unset($validated['current_password']);

        $pengguna->update($validated);

        return redirect()->route('pengguna.index')
                        ->with('success', 'Data pengguna berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengguna $pengguna)
    {
        // Cek apakah pengguna sedang login
        if ($pengguna->id_pengguna === Auth::id()) {
            return back()->withErrors([
                'error' => 'Tidak dapat menghapus akun yang sedang login!'
            ]);
        }


        // Cek apakah pengguna memiliki transaksi atau pengeluaran
        if ($pengguna->transaksiPembayaran()->count() > 0 || $pengguna->pengeluaran()->count() > 0) {
            return back()->withErrors([
                'error' => 'Pengguna tidak dapat dihapus karena memiliki riwayat transaksi atau pengeluaran.'
            ]);
        }

        $pengguna->delete();

        return redirect()->route('pengguna.index')
                        ->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Reset password pengguna
     */
    public function resetPassword(Request $request, Pengguna $pengguna)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Password lama harus diisi.',
        ]);

        // Verifikasi password lama
        if (!Hash::check($request->current_password, $pengguna->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama tidak sesuai.'
            ])->withInput();
        }

        $pengguna->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return back()->with('success', 'Password berhasil direset.');
    }
}
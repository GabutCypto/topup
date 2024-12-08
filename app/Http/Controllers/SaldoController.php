<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SaldoController extends Controller
{
    // Menampilkan form untuk membuat permohonan topup
    public function create()
    {
        return view('user.saldo.create');  // Mengarahkan ke halaman pembuatan saldo (topup)
    }

    // Menyimpan permohonan topup
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'saldo' => 'required|numeric|min:1000', // Validasi saldo minimum 1000
            'proof' => 'nullable|file|mimes:jpeg,png,pdf,jpg|max:2048', // Batasan file untuk bukti pembayaran
        ]);

        try {
            // Simpan bukti pembayaran jika ada
            if ($request->hasFile('proof')) {
                $filePath = $request->file('proof')->store('payment_proofs', 'public');
            } else {
                $filePath = null;  // Jika tidak ada bukti pembayaran
            }

            // Simpan data saldo
            Saldo::create([
                'user_id' => auth()->id(),  // Relasi dengan pengguna yang login
                'saldo' => $request->saldo,
                'proof' => $filePath,
                'sudah_dibayar' => false,  // Status awal adalah belum dibayar
            ]);

            return redirect()->route('user.saldo.index')->with('success', 'Topup request submitted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('user.saldo.index')->with('error', 'Error submitting the topup: ' . $e->getMessage());
        }
    }

    // Menampilkan daftar topup untuk user atau owner
    public function index()
    {
        $user = Auth::user();

        // Jika role adalah 'owner', tampilkan semua saldo
        // Jika role adalah 'buyer', tampilkan hanya saldo milik user tersebut
        if ($user->hasRole('owner')) {
            $saldo = Saldo::orderBy('created_at', 'desc')->get();
        } else {
            $saldo = $user->saldos()->orderBy('created_at', 'desc')->get();  
        }

        return view('user.saldo.index', compact('saldo'));
    }

    // Mengupdate status saldo menjadi 'sukses' setelah diverifikasi oleh owner
    public function update(Request $request, Saldo $saldo)
    {
        // Pastikan hanya owner yang bisa mengubah status saldo
        if (Auth::user()->hasRole('owner')) {
            // Periksa apakah saldo belum disetujui (sudah_dibayar == false)
            if (!$saldo->sudah_dibayar) {
                $saldo->sudah_dibayar = true;  // Ubah status saldo menjadi sukses
                $saldo->save();

                return redirect()->route('user.saldo.index')->with('success', 'Topup approved successfully!');
            }

            // Jika sudah disetujui sebelumnya
            return redirect()->route('user.saldo.index')->with('info', 'Topup has already been approved.');
        }

        return redirect()->route('user.saldo.index')->with('error', 'You do not have permission to approve this topup.');
    }

    // Menampilkan detail saldo yang sudah dibuat
    public function show(Saldo $saldo)
    {
        // Pastikan saldo yang diminta milik pengguna yang sedang login atau owner
        if (Auth::user()->id !== $saldo->user_id && !Auth::user()->hasRole('owner')) {
            return redirect()->route('user.saldo.index')->with('error', 'You are not authorized to view this saldo.');
        }

        return view('user.saldo.show', compact('saldo'));
    }
}
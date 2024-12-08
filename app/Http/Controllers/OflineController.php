<?php

namespace App\Http\Controllers;

use App\Models\Ofline;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OflineController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:owner|buyer')->only(['index', 'show']);
        $this->middleware('role:owner')->only(['create', 'store']);
    }

    // Menampilkan daftar transaksi (bisa dilihat oleh owner dan buyer)
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('owner')) {
            // Owner hanya bisa melihat transaksi yang terkait dengannya
            $transactions = Ofline::where('owner_id', Auth::id())->get();
        } elseif ($user->hasRole('buyer')) {
            // Buyer hanya bisa melihat transaksi yang dibuat untuknya
            $transactions = Ofline::where('buyer_id', Auth::id())->get();
        }

        return view('admin.ofline.index', compact('transactions'));
    }

    // Menampilkan form untuk membuat transaksi (hanya untuk owner)
    public function create()
    {
        $produk = Produk::all(); // Ambil semua produk
        $buyers = User::role('buyer')->get(); // Ambil semua buyer
        return view('admin.ofline.create', compact('produk', 'buyers'));
    }

    public function store(Request $request)
    {
        // Mendapatkan buyer yang dipilih
        $buyer = User::findOrFail($request->buyer_id);
        
        // Menghitung total harga
        $totalHarga = 0;
        foreach ($request->produk as $produkData) {
            $produk = Produk::findOrFail($produkData['id']);
            $totalHarga += $produk->harga * $produkData['quantity'];
        }

        // Jika role owner, transaksi tetap berjalan meski saldo tidak dicek
        if (Auth::user()->hasRole('owner')) {
            // Mengurangi saldo buyer tanpa validasi
            $buyer->saldo -= $totalHarga;
            $buyer->save();
        } else {
            // Mengecek apakah saldo buyer cukup jika role adalah buyer
            if ($buyer->saldo < $totalHarga) {
                return redirect()->back()->with('error', 'Saldo tidak mencukupi');
            }

            // Mengurangi saldo buyer
            $buyer->saldo -= $totalHarga;
            $buyer->save();
        }

        // Menangani upload foto jika ada
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        // Membuat transaksi untuk setiap produk yang dibeli
        foreach ($request->produk as $produkData) {
            $produk = Produk::findOrFail($produkData['id']);

            // Membuat entri transaksi
            Ofline::create([
                'buyer_id' => $buyer->id,
                'owner_id' => Auth::id(),
                'produk_id' => $produk->id, // Menyimpan produk_id
                'amount' => $produk->harga * $produkData['quantity'], // Menyimpan amount untuk produk
                'quantity' => $produkData['quantity'], // Menyimpan quantity
                'photo' => $photoPath, // Menyimpan path foto produk (jika ada)
            ]);
        }

        return redirect()->route('ofline.index')->with('success', 'Transaksi berhasil');
    }

    // Menampilkan detail transaksi (bisa dilihat oleh buyer)
    public function show($id)
    {
        $transaction = Ofline::findOrFail($id);
        return view('admin.ofline.show', compact('transaction'));
    }
}
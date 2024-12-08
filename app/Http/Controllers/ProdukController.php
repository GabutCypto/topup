<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $kategoriId = $request->input('kategori_id');
    $tokoId = $request->input('toko_id');

    $kategori = Kategori::all();
    $toko = Toko::all();

    $produk = Produk::with(['toko', 'kategori'])
                    ->when($kategoriId, function($query, $kategoriId) {
                        return $query->whereHas('kategori', function($q) use ($kategoriId) {
                            $q->where('id', $kategoriId);
                        });
                    })
                    ->when($tokoId, function($query, $tokoId) {
                        return $query->where('toko_id', $tokoId);
                    })
                    ->orderBy('id', 'DESC')
                    ->get();

    return view('admin.produk.index', [
        'produk' => $produk,
        'kategori' => $kategori,
        'toko' => $toko
    ]);
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $toko = Toko::all();
        $kategori = Kategori::all();

        return view('admin.produk.create', [
            'toko' => $toko,
            'kategori' => $kategori
        ]);


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tentang' => 'required|string',
            'toko_id' => 'required|integer',
            'kategori_id' => 'required|integer',
            'harga' => 'required|integer',
            'foto' => 'required|image|mimes:png,jpg,svg,jpeg',
        ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('foto')) {
                $photoPath = $request->file('foto')->store('foto', 'public');
                $validated['foto'] = $photoPath;
            }
            $validated['slug'] = Str::slug($request->nama);

            $buatProduct = Produk::create($validated);

            DB::commit();

            return redirect()->route('admin.produk.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'system_error' => ['System error!' . $e->getMessage()],
            ]);
            throw $error;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        //
        $toko = Toko::all();
        $kategori = Kategori::all();

        return view('admin.produk.edit', [
            'produk' => $produk,
            'toko' => $toko,
            'kategori' => $kategori
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        //
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tentang' => 'required|string',
            'toko_id' => 'required|integer',
            'kategori_id' => 'required|integer',
            'harga' => 'required|integer',
            'foto' => 'required|image|mimes:png,jpg,svg,jpeg',
        ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($produk->foto) {
                    Storage::delete('public/' . $produk->foto);
                }
    
                // Simpan foto baru
                $photoPath = $request->file('foto')->store('foto', 'public');
                $validated['foto'] = $photoPath;
            }
    
            // Generate slug dari name
            $validated['slug'] = Str::slug($validated['nama']);
    
            // Update produk
            $produk->update($validated);
    
            DB::commit();
    
            return redirect()->route('admin.produk.index')->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'system_error' => 'System error: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        //
        try {
            // Hapus foto kategori dari storage jika ada
            if ($produk->foto) {
                Storage::delete('public/' . $produk->foto);
            }

            // Hapus kategori
            $produk->delete();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'system_error' => ['System error!' . $e->getMessage()],
            ]);
            throw $error;
        }
    }
}
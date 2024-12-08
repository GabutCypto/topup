<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $kategori = Kategori::with('toko')->orderBy('id', 'DESC')->get();
        return view('admin.kategori.index', [
            'kategori' => $kategori
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $toko = Toko::all();
        return view('admin.kategori.create', [
            'toko' => $toko
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
            'toko_id' => 'required|integer',
            'ikon' => 'required|image|mimes:png,jpg,svg,jpeg',
        ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('ikon')) {
                $ikonPath = $request->file('ikon')->store('ikon', 'public');
                $validated['ikon'] = $ikonPath;
            }
            $validated['slug'] = Str::slug($request->nama);

            $newProduct = Kategori::create($validated);

            DB::commit();

            return redirect()->route('admin.kategori.index');
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
    public function show(Kategori $kategori)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori)
    {
        //
        $toko = Toko::all();
        return view('admin.kategori.edit', [
            'kategori' => $kategori,
            'toko' => $toko
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        //
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'toko_id' => 'required|integer',
            'ikon' => 'required|image|mimes:png,jpg,svg,jpeg',
        ]);
    
        DB::beginTransaction();
    
        try {
            if ($request->hasFile('ikon')) {
                // Hapus foto lama jika ada
                if ($kategori->ikon) {
                    Storage::delete('public/' . $kategori->ikon);
                }
    
                // Simpan foto baru
                $ikonPath = $request->file('ikon')->store('ikon', 'public');
                $validated['ikon'] = $ikonPath;
            }
    
            // Generate slug dari name
            $validated['slug'] = Str::slug($validated['nama']);
    
            // Update produk
            $kategori->update($validated);
    
            DB::commit();
    
            return redirect()->route('admin.kategori.index')->with('success', 'Kategori updated successfully!');
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
    public function destroy(Kategori $kategori)
    {
        //
        try {
            // Hapus foto kategori dari storage jika ada
            if ($kategori->ikon) {
                Storage::delete('public/' . $kategori->ikon);
            }

            // Hapus kategori
            $kategori->delete();
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
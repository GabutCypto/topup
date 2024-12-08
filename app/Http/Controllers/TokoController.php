<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class TokoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $toko = Toko::all();
        return view('admin.toko.index', [
            'toko' => $toko
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.toko.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'ikon' => 'required|image|mimes:png,jpg,svg,jpeg',
        ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('ikon')) {
                $ikonPath = $request->file('ikon')->store('Toko', 'public');
                $validated['ikon'] = $ikonPath;
            }
            $validated['slug'] = Str::slug($request->nama);

            $buatToko = Toko::create($validated);

            DB::commit();

            return redirect()->route('admin.toko.index');
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
    public function show(Toko $toko)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Toko $toko)
    {
        //
        return view('admin.toko.edit', [
            'toko' => $toko
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Toko $toko)
    {
        //
        $validated = $request->validate([
            'nama' => 'sometimes|string|max:255',
            'ikon' => 'sometimes|image|mimes:png,jpg,svg,jpeg',
        ]);

        DB::beginTransaction();

        try {
            // Cek apakah ada file icon baru yang diupload
            if ($request->hasFile('ikon')) {
                // Hapus foto lama jika ada
                if ($toko->icon) {
                    Storage::delete('public/' . $toko->icon);
                }

                // Simpan foto baru
                $iconPath = $request->file('ikon')->store('toko', 'public');
                $validated['ikon'] = $iconPath;
            }

            // Set slug
            $validated['slug'] = Str::slug($request->nama);

            // Update kategori
            $toko->update($validated);

            DB::commit();

            return redirect()->route('admin.toko.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'system_error' => ['System error!' . $e->getMessage()],
            ]);
            throw $error;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Toko $toko)
    {
        //
        try {
            // Hapus foto kategori dari storage jika ada
            if ($toko->ikon) {
                Storage::delete('public/' . $toko->ikon);
            }

            // Hapus kategori
            $toko->delete();
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
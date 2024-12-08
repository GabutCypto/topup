<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    //
    protected $guarded = [
        'id',
    ];

    public function kategori()
    {
        return $this->hasMany(Kategori::class);
    }

    public function produk()
    {
        return $this->hasMany(Produk::class);
    }
}
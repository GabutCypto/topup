<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    //
    protected $guarded = [
        'id',
    ];

    public function toko(){
        return $this->belongsTo(Toko::class);
    }

    public function produk(){
        return $this->belongsTo(Produk::class);
    }
}
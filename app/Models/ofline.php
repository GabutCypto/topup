<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ofline extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'buyer_id',
        'produk_id',
        'saldo_id',
        'amount',
        'quantity',
        'photo',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function saldo()
    {
        return $this->belongsTo(Saldo::class, 'saldo_id');
    }
}
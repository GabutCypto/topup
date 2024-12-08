<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    //
    protected $guarded = [
        'id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi__poduks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  
            $table->unsignedBigInteger('jumlah_total');  
            $table->boolean('sudah_dibayar')->default(false); 
            $table->string('nama');  
            $table->string('ruang');  
            $table->text('catatan'); 
            $table->string('nomor_telepon');
            $table->string('foto'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi__poduks');
    }
};
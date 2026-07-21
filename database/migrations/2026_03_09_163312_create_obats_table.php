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
        Schema::create('obats', function (Blueprint $table) {
            $table->id('id_obat');
            $table->string('kode_obat', 20)->unique();
            $table->string('nama_obat', 100);
            $table->enum('satuan', ['tablet', 'kapsul', 'botol', 'sachet', 'sirup', 'salep']);
            $table->integer('stok_tersedia')->default(0);
            $table->integer('stok_minimal')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obats');
    }
};

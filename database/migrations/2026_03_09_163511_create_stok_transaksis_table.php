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
        Schema::create('stok_transaksis', function (Blueprint $table) {
            $table->id('id_stok_transaksi');
            $table->unsignedBigInteger('id_obat');
            $table->enum('jenis_transaksi', ['masuk', 'keluar']);
            $table->integer('jumlah');
            $table->dateTime('tanggal_transaksi');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('id_user')->nullable(); // user yang mendata atau dokter
            $table->timestamps();

            $table->foreign('id_obat')->references('id_obat')->on('obats')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_transaksis');
    }
};

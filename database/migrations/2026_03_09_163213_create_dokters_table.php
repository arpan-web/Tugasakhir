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
        Schema::create('dokters', function (Blueprint $table) {
            $table->id('id_dokter');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_poli');
            $table->string('nama_dokter', 100);
            $table->string('spesialisasi', 100)->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->string('jadwal_praktek', 100)->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_poli')->references('id_poli')->on('polis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokters');
    }
};

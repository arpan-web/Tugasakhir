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
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id('id_pendaftaran');
            $table->string('nomor_pendaftaran', 20)->unique();
            $table->dateTime('tanggal_daftar');
            $table->text('keluhan');
            $table->integer('no_antrian');
            $table->decimal('suhu_tubuh', 4, 1)->nullable();
            $table->decimal('berat_badan', 5, 2)->nullable();
            $table->decimal('tinggi_badan', 5, 2)->nullable();
            $table->enum('status_pendaftaran', ['baru', 'diproses', 'selesai', 'batal'])->default('baru');
            
            // Foreign Keys
            $table->unsignedBigInteger('id_pasien');
            $table->unsignedBigInteger('id_poli');
            $table->unsignedBigInteger('id_dokter');
            $table->unsignedBigInteger('id_perawat')->nullable();
            $table->unsignedBigInteger('id_user')->nullable(); // user yang mendata

            $table->timestamps();

            // Constraints
            $table->foreign('id_pasien')->references('id_pasien')->on('pasiens')->onDelete('cascade');
            $table->foreign('id_poli')->references('id_poli')->on('polis')->onDelete('cascade');
            $table->foreign('id_dokter')->references('id_dokter')->on('dokters')->onDelete('cascade');
            $table->foreign('id_perawat')->references('id_perawat')->on('perawats')->onDelete('set null');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};

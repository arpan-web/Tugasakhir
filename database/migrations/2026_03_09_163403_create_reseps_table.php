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
        Schema::create('reseps', function (Blueprint $table) {
            $table->id('id_resep');
            $table->string('nomor_resep', 20)->unique();
            $table->dateTime('tanggal_resep');
            $table->unsignedBigInteger('id_diagnosa');
            $table->unsignedBigInteger('id_dokter');
            $table->timestamps();

            $table->foreign('id_diagnosa')->references('id_diagnosa')->on('diagnosas')->onDelete('cascade');
            $table->foreign('id_dokter')->references('id_dokter')->on('dokters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseps');
    }
};

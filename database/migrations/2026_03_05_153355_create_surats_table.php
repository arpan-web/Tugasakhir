<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('surats', function (Blueprint $table) {
        $table->id();
        $table->string('nomor_surat');
        $table->string('judul');
        $table->string('pengirim');
        $table->date('tanggal');
        $table->string('file_surat');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};

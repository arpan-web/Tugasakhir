<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stok_transaksis', function (Blueprint $table) {
            $table->integer('sisa_stok')->default(0)->after('jumlah');
            $table->date('tanggal_kadaluarsa')->nullable()->after('keterangan');
        });

        // Set sisa_stok = jumlah for existing 'masuk' records
        DB::table('stok_transaksis')->where('jenis_transaksi', 'masuk')->update([
            'sisa_stok' => DB::raw('jumlah')
        ]);

        Schema::table('notifikasis', function (Blueprint $table) {
            $table->enum('tipe', ['stok', 'antrian', 'kadaluarsa'])->default('stok')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stok_transaksis', function (Blueprint $table) {
            $table->dropColumn(['sisa_stok', 'tanggal_kadaluarsa']);
        });

        Schema::table('notifikasis', function (Blueprint $table) {
            $table->enum('tipe', ['stok', 'antrian'])->default('stok')->change();
        });
    }
};

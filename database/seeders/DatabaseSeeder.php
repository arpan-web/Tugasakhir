<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat User Admin
        \App\Models\User::firstOrCreate([
            'username' => 'admin',
        ], [
            'nama_user' => 'Administrator',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        // Buat beberapa Poli dasar
        \App\Models\Poli::firstOrCreate(['nama_poli' => 'Poli Umum'], ['keterangan' => 'Pelayanan medis umum']);
        \App\Models\Poli::firstOrCreate(['nama_poli' => 'Poli Gigi'], ['keterangan' => 'Pelayanan kesehatan gigi']);
        
        // Buat beberapa Obat dasar
        \App\Models\Obat::firstOrCreate(['kode_obat' => 'OBT001'], [
            'nama_obat' => 'Paracetamol 500mg',
            'satuan' => 'tablet',
            'stok_tersedia' => 100,
            'stok_minimal' => 20,
            'keterangan' => 'Pereda nyeri dan demam'
        ]);
        \App\Models\Obat::firstOrCreate(['kode_obat' => 'OBT002'], [
            'nama_obat' => 'Amoxicillin 500mg',
            'satuan' => 'kapsul',
            'stok_tersedia' => 50,
            'stok_minimal' => 15,
            'keterangan' => 'Antibiotik'
        ]);
    }
}

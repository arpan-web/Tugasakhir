<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Poli;
use App\Models\Dokter;
use App\Models\Perawat;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pendaftaran;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. User Admin
        $admin = User::firstOrCreate([
            'username' => 'admin',
        ], [
            'nama_user' => 'Administrator',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        // 2. User Dokter
        $userDokter = User::firstOrCreate([
            'username' => 'dokter1',
        ], [
            'nama_user' => 'dr. Andi Prasetyo, Sp.PD',
            'password' => bcrypt('dokter123'),
            'role' => 'dokter',
            'status' => 'aktif',
        ]);

        // 3. User Perawat
        $userPerawat = User::firstOrCreate([
            'username' => 'perawat1',
        ], [
            'nama_user' => 'Ns. Siti Rahma, S.Kep',
            'password' => bcrypt('perawat123'),
            'role' => 'perawat',
            'status' => 'aktif',
        ]);

        // 4. Data Poli
        $poliUmum = Poli::firstOrCreate(['nama_poli' => 'Poli Umum'], ['keterangan' => 'Pelayanan medis umum']);
        $poliGigi = Poli::firstOrCreate(['nama_poli' => 'Poli Gigi'], ['keterangan' => 'Pelayanan kesehatan gigi']);
        
        // 5. Data Dokter (relasi ke User & Poli)
        $dokter = Dokter::firstOrCreate([
            'id_user' => $userDokter->id_user,
        ], [
            'id_poli' => $poliUmum->id_poli,
            'nama_dokter' => 'dr. Andi Prasetyo, Sp.PD',
            'spesialisasi' => 'Penyakit Dalam',
            'no_hp' => '081234567890',
            'jadwal_praktek' => 'Senin - Jumat (08.00 - 15.00)'
        ]);

        // 6. Data Perawat (relasi ke User)
        $perawat = Perawat::firstOrCreate([
            'id_user' => $userPerawat->id_user,
        ], [
            'nama_perawat' => 'Ns. Siti Rahma, S.Kep',
            'no_hp' => '089876543210'
        ]);

        // 7. Data Obat
        Obat::firstOrCreate(['kode_obat' => 'OBT001'], [
            'nama_obat' => 'Paracetamol 500mg',
            'satuan' => 'tablet',
            'stok_tersedia' => 100,
            'stok_minimal' => 20,
            'keterangan' => 'Pereda nyeri dan demam'
        ]);

        Obat::firstOrCreate(['kode_obat' => 'OBT002'], [
            'nama_obat' => 'Amoxicillin 500mg',
            'satuan' => 'kapsul',
            'stok_tersedia' => 50,
            'stok_minimal' => 15,
            'keterangan' => 'Antibiotik'
        ]);

        Obat::firstOrCreate(['kode_obat' => 'OBT003'], [
            'nama_obat' => 'Vitamin C 500mg',
            'satuan' => 'tablet',
            'stok_tersedia' => 200,
            'stok_minimal' => 30,
            'keterangan' => 'Suplemen daya tahan tubuh'
        ]);

        // 8. Data Pasien
        $pasien1 = Pasien::firstOrCreate(['nomor_pasien' => 'PAS-0001'], [
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2002-05-14',
            'alamat' => 'Jl. Ahmad Yani No. 12, Pontianak',
            'no_hp' => '081399887766',
            'status_pasien' => 'Polnep',
            'nomor_kartu_pasien' => 'NIM32021001'
        ]);

        $pasien2 = Pasien::firstOrCreate(['nomor_pasien' => 'PAS-0002'], [
            'nama_lengkap' => 'Siti Nurhaliza',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '2001-11-20',
            'alamat' => 'Jl. Gajah Mada No. 45, Pontianak',
            'no_hp' => '085211223344',
            'status_pasien' => 'Umum',
            'nomor_kartu_pasien' => 'KTP61710101'
        ]);

        // 9. Data Pendaftaran Antrian Sampel
        Pendaftaran::firstOrCreate(['nomor_pendaftaran' => 'REG-' . date('Ymd') . '-001'], [
            'tanggal_daftar' => now(),
            'keluhan' => 'Demam dan pusing sejak kemarin',
            'no_antrian' => 1,
            'suhu_tubuh' => 37.8,
            'berat_badan' => 62.5,
            'tinggi_badan' => 168.0,
            'status_pendaftaran' => 'baru',
            'id_pasien' => $pasien1->id_pasien,
            'id_poli' => $poliUmum->id_poli,
            'id_dokter' => $dokter->id_dokter,
            'id_perawat' => $perawat->id_perawat,
            'id_user' => $admin->id_user,
        ]);
    }
}


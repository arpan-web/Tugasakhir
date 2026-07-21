<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pasien;

class PasienController extends Controller
{
    public function index()
    {
        $pasiens = Pasien::orderBy('created_at', 'desc')->paginate(10);
        return view('pasien.index', compact('pasiens'));
    }

    public function create()
    {
        return view('pasien.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:12',
            'status_pasien' => 'required|in:Polnep,Umum',
        ]);

        // Generate Nomor Pasien (contoh format: P001)
        $lastPasien = Pasien::orderBy('id_pasien', 'desc')->first();
        $nextId = $lastPasien ? $lastPasien->id_pasien + 1 : 1;
        $nomor_pasien = 'P' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $pasien = Pasien::create([
            'nomor_pasien' => $nomor_pasien,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'status_pasien' => $request->status_pasien,
            'nomor_kartu_pasien' => $request->nomor_kartu_pasien ?? null,
        ]);

        return redirect()->route('pendaftaran.create', ['id_pasien' => $pasien->id_pasien])->with('success', 'Data Pasien berhasil ditambahkan. Silakan lanjutkan pendaftaran antrian.');
    }

    public function edit(string $id)
    {
        $pasien = Pasien::findOrFail($id);
        return view('pasien.edit', compact('pasien'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:12',
            'status_pasien' => 'required|in:Polnep,Umum',
        ]);

        $pasien = Pasien::findOrFail($id);
        $pasien->update($request->all());

        return redirect()->route('pasien.index')->with('success', 'Data Pasien berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $pasien = Pasien::findOrFail($id);
        $pasien->delete();

        return redirect()->route('pasien.index')->with('success', 'Data Pasien berhasil dihapus.');
    }
}

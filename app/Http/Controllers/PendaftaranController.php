<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pendaftaran;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\Dokter;
use App\Models\Perawat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PendaftaranController extends Controller
{
    public function index()
    {
        $pendaftarans = Pendaftaran::with(['pasien', 'poli', 'dokter'])
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(10);
        return view('pendaftaran.index', compact('pendaftarans'));
    }

    public function create()
    {
        $pasiens = Pasien::all();
        $polis = Poli::all();
        $dokters = Dokter::with('user')->get();
        return view('pendaftaran.create', compact('pasiens', 'polis', 'dokters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pasien' => 'required|exists:pasiens,id_pasien',
            'id_poli' => 'required|exists:polis,id_poli',
            'id_dokter' => 'required|exists:dokters,id_dokter',
            'keluhan' => 'required|string',
        ]);

        $today = Carbon::today();

        // Cari perawat yang sedang login (jika role perawat)
        $id_perawat = null;
        if (Auth::user()->role == 'perawat') {
            $perawat = Perawat::where('id_user', Auth::id())->first();
            $id_perawat = $perawat ? $perawat->id_perawat : null;
        }

        // Generate Nomor Antrian per hari
        $lastAntrian = Pendaftaran::whereDate('tanggal_daftar', $today)->max('no_antrian');
        $no_antrian = $lastAntrian ? $lastAntrian + 1 : 1;

        // Generate Nomor Pendaftaran
        $nomor_pendaftaran = 'REG-' . date('Ymd') . '-' . str_pad($no_antrian, 3, '0', STR_PAD_LEFT);

        Pendaftaran::create([
            'nomor_pendaftaran' => $nomor_pendaftaran,
            'tanggal_daftar' => now(),
            'keluhan' => $request->keluhan,
            'no_antrian' => $no_antrian,
            'suhu_tubuh' => $request->suhu_tubuh,
            'berat_badan' => $request->berat_badan,
            'tinggi_badan' => $request->tinggi_badan,
            'status_pendaftaran' => 'baru',
            'id_pasien' => $request->id_pasien,
            'id_poli' => $request->id_poli,
            'id_dokter' => $request->id_dokter,
            'id_perawat' => $id_perawat,
            'id_user' => Auth::id()
        ]);

        return redirect()->route('pendaftaran.index')->with('success', 'Pendaftaran antrian berhasil dibuat.');
    }

    public function show(string $id)
    {
        $pendaftaran = Pendaftaran::with(['pasien', 'poli', 'dokter', 'perawat'])->findOrFail($id);
        return view('pendaftaran.show', compact('pendaftaran'));
    }

    public function edit(string $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pasiens = Pasien::all();
        $polis = Poli::all();
        $dokters = Dokter::with('user')->get();
        return view('pendaftaran.edit', compact('pendaftaran', 'pasiens', 'polis', 'dokters'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'status_pendaftaran' => 'required|in:baru,diproses,selesai,batal',
        ]);

        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->update([
            'suhu_tubuh' => $request->suhu_tubuh,
            'berat_badan' => $request->berat_badan,
            'tinggi_badan' => $request->tinggi_badan,
            'status_pendaftaran' => $request->status_pendaftaran,
        ]);

        return redirect()->route('pendaftaran.index')->with('success', 'Data Pendaftaran berhasil di-update.');
    }

    public function destroy(string $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $pendaftaran->delete();

        return redirect()->route('pendaftaran.index')->with('success', 'Data pendaftaran dihapus.');
    }
}

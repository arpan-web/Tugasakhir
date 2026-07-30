<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Dokter;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class DokterController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user() && Auth::user()->role !== 'admin') {
                abort(403, 'Akses ditolak. Hanya Admin yang dapat mengelola data master Dokter.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $dokters = Dokter::with(['poli', 'user'])->orderBy('created_at', 'desc')->paginate(10);
        return view('dokter.index', compact('dokters'));
    }

    public function create()
    {
        $polis = Poli::all();
        return view('dokter.create', compact('polis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dokter' => 'required|string|max:100',
            'spesialisasi' => 'nullable|string|max:100',
            'no_hp' => 'nullable|string|max:15',
            'jadwal_praktek' => 'nullable|string|max:100',
            'id_poli' => 'required|exists:polis,id_poli',
        ]);

        DB::beginTransaction();
        try {
            // Generate user account for doctor
            $username = 'dr_' . strtolower(preg_replace('/\s+/', '', $request->nama_dokter));
            
            // Check if username already exists to avoid unique constraint error
            $count = User::where('username', 'LIKE', "{$username}%")->count();
            if ($count > 0) {
                $username = $username . $count;
            }

            $user = User::create([
                'nama_user' => 'Dr. ' . $request->nama_dokter,
                'username' => $username,
                'password' => Hash::make('dokter123'), // Default password
                'role' => 'dokter',
                'status' => 'aktif'
            ]);

            Dokter::create([
                'id_user' => $user->id_user,
                'id_poli' => $request->id_poli,
                'nama_dokter' => $request->nama_dokter,
                'spesialisasi' => $request->spesialisasi,
                'no_hp' => $request->no_hp,
                'jadwal_praktek' => $request->jadwal_praktek,
            ]);

            DB::commit();
            return redirect()->route('dokter.index')->with('success', 'Data Dokter berhasil ditambahkan bersama dengan Akun User (Password Default: dokter123).');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function edit(string $id)
    {
        $dokter = Dokter::findOrFail($id);
        $polis = Poli::all();
        return view('dokter.edit', compact('dokter', 'polis'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_dokter' => 'required|string|max:100',
            'spesialisasi' => 'nullable|string|max:100',
            'no_hp' => 'nullable|string|max:15',
            'jadwal_praktek' => 'nullable|string|max:100',
            'id_poli' => 'required|exists:polis,id_poli',
        ]);

        $dokter = Dokter::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $dokter->update([
                'id_poli' => $request->id_poli,
                'nama_dokter' => $request->nama_dokter,
                'spesialisasi' => $request->spesialisasi,
                'no_hp' => $request->no_hp,
                'jadwal_praktek' => $request->jadwal_praktek,
            ]);

            // Sync user name
            if ($dokter->user) {
                $dokter->user->update([
                    'nama_user' => 'Dr. ' . $request->nama_dokter
                ]);
            }

            DB::commit();
            return redirect()->route('dokter.index')->with('success', 'Data Dokter berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal memperbarui data: ' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        $dokter = Dokter::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $userId = $dokter->id_user;
            $dokter->delete();
            
            // Hapus user terkait jika ada
            if ($userId) {
                User::where('id_user', $userId)->delete();
            }

            DB::commit();
            return redirect()->route('dokter.index')->with('success', 'Data Dokter dan Akun Login bersangkutan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menghapus data: ' . $e->getMessage()]);
        }
    }
}

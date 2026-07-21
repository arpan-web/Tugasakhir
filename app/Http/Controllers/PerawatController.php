<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Perawat;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PerawatController extends Controller
{
    public function index()
    {
        $perawats = Perawat::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('perawat.index', compact('perawats'));
    }

    public function create()
    {
        return view('perawat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_perawat' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:15',
        ]);

        DB::beginTransaction();
        try {
            $username = 'ns_' . strtolower(preg_replace('/\s+/', '', $request->nama_perawat));
            
            $count = User::where('username', 'LIKE', "{$username}%")->count();
            if ($count > 0) {
                $username = $username . $count;
            }

            $user = User::create([
                'nama_user' => 'Ns. ' . $request->nama_perawat,
                'username' => $username,
                'password' => Hash::make('perawat123'),
                'role' => 'perawat',
                'status' => 'aktif'
            ]);

            Perawat::create([
                'id_user' => $user->id_user,
                'nama_perawat' => $request->nama_perawat,
                'no_hp' => $request->no_hp,
            ]);

            DB::commit();
            return redirect()->route('perawat.index')->with('success', 'Data Perawat beserta Akun Login berhasil ditambahkan (Password: perawat123).');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    public function edit(string $id)
    {
        $perawat = Perawat::findOrFail($id);
        return view('perawat.edit', compact('perawat'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_perawat' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:15',
        ]);

        $perawat = Perawat::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $perawat->update([
                'nama_perawat' => $request->nama_perawat,
                'no_hp' => $request->no_hp,
            ]);

            if ($perawat->user) {
                $perawat->user->update([
                    'nama_user' => 'Ns. ' . $request->nama_perawat
                ]);
            }

            DB::commit();
            return redirect()->route('perawat.index')->with('success', 'Data Perawat berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal memperbarui: ' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        $perawat = Perawat::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $userId = $perawat->id_user;
            $perawat->delete();
            
            if ($userId) {
                User::where('id_user', $userId)->delete();
            }

            DB::commit();
            return redirect()->route('perawat.index')->with('success', 'Data Perawat & Akun terhapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }
}

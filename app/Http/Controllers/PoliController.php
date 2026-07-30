<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Poli;

use Illuminate\Support\Facades\Auth;

class PoliController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user() && Auth::user()->role !== 'admin') {
                abort(403, 'Akses ditolak. Hanya Admin yang dapat mengelola data master Poliklinik.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $polis = Poli::orderBy('created_at', 'desc')->paginate(10);
        return view('poli.index', compact('polis'));
    }

    public function create()
    {
        return view('poli.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_poli' => 'required|string|max:100',
            'keterangan' => 'nullable|string'
        ]);

        Poli::create($request->all());

        return redirect()->route('poli.index')->with('success', 'Data Poliklinik berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $poli = Poli::findOrFail($id);
        return view('poli.edit', compact('poli'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_poli' => 'required|string|max:100',
            'keterangan' => 'nullable|string'
        ]);

        $poli = Poli::findOrFail($id);
        $poli->update($request->all());

        return redirect()->route('poli.index')->with('success', 'Data Poliklinik berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $poli = Poli::findOrFail($id);
        $poli->delete();

        return redirect()->route('poli.index')->with('success', 'Data Poliklinik berhasil dihapus.');
    }
}

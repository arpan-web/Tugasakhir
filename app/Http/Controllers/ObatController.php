<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Obat;

class ObatController extends Controller
{
    public function index()
    {
        $obats = Obat::orderBy('nama_obat', 'asc')->paginate(10);
        return view('obat.index', compact('obats'));
    }

    public function create()
    {
        return view('obat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_obat' => 'required|string|max:20|unique:obats,kode_obat',
            'nama_obat' => 'required|string|max:100',
            'satuan' => 'required|in:tablet,kapsul,botol,sachet,sirup,salep',
            'stok_tersedia' => 'required|integer|min:0',
            'stok_minimal' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        Obat::create($request->all());

        return redirect()->route('obat.index')->with('success', 'Data Obat/Barang Farmasi berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $obat = Obat::findOrFail($id);
        return view('obat.edit', compact('obat'));
    }

    public function update(Request $request, string $id)
    {
        $obat = Obat::findOrFail($id);

        $request->validate([
            'kode_obat' => 'required|string|max:20|unique:obats,kode_obat,' . $obat->id_obat . ',id_obat',
            'nama_obat' => 'required|string|max:100',
            'satuan' => 'required|in:tablet,kapsul,botol,sachet,sirup,salep',
            'stok_tersedia' => 'required|integer|min:0',
            'stok_minimal' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $obat->update($request->all());

        return redirect()->route('obat.index')->with('success', 'Data Obat berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $obat = Obat::findOrFail($id);
        $obat->delete();

        return redirect()->route('obat.index')->with('success', 'Data Obat berhasil dihapus beserta seluruh riwayat terkait.');
    }
}

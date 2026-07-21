<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;

class SuratController extends Controller
{
    public function index()
    {
        $surat = Surat::all();
        return view('surat.index', compact('surat'));
    }

    public function create()
    {
        return view('surat.create');
    }

    public function store(Request $request)
    {
        $file = $request->file('file_surat');
        $namaFile = time().'.'.$file->extension();
        $file->move(public_path('surat'), $namaFile);

        Surat::create([
            'nomor_surat' => $request->nomor_surat,
            'judul' => $request->judul,
            'pengirim' => $request->pengirim,
            'tanggal' => $request->tanggal,
            'file_surat' => $namaFile
        ]);

        return redirect('/surat');
    }

    public function destroy($id)
    {
        Surat::destroy($id);
        return redirect('/surat');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    /**
     * Tandai satu notifikasi sebagai sudah dibaca dan redirect ke halaman terkait.
     */
    public function baca(string $id)
    {
        $notif = Notifikasi::findOrFail($id);
        $notif->update(['status' => 'dibaca']);

        // Arahkan ke halaman yang relevan sesuai tipe notifikasi
        if ($notif->tipe === 'stok') {
            return redirect()->route('obat.index');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca.
     */
    public function bacaSemua()
    {
        Notifikasi::where('status', 'belum_dibaca')->update(['status' => 'dibaca']);
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pendaftaran';
    protected $guarded = [];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter');
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }

    public function perawat()
    {
        return $this->belongsTo(Perawat::class, 'id_perawat');
    }

    public function diagnosa()
    {
        return $this->hasOne(Diagnosa::class, 'id_pendaftaran');
    }
}

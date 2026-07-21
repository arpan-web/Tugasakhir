<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanHarianObat extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_catatan';
    protected $guarded = [];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
}

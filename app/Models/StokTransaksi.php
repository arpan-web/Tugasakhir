<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokTransaksi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_stok_transaksi';
    protected $guarded = [];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}

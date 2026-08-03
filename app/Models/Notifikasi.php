<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasis';
    protected $primaryKey = 'id_notif';

    protected $fillable = [
        'pesan',
        'tipe',
        'status',
    ];
}

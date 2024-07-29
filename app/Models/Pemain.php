<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemain extends Model
{
    use HasFactory;
    protected $fillable = ['nama_pemain', 'posisi', 'foto', 'tgl_lahir', 'harga_pasar', 'id_klub'];

    public function klub()
    {
        return $this->belongsTo(Klub::class, 'id_klub');
    }

    public function pemain()
    {
        return $this->hasMany(Pemain::class, 'id_klub');
    }
}

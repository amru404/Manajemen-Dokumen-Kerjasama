<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mitra extends Model
{
    protected $table = 'mitras';

    protected $fillable = [
        'nama',
        'penanggung_jawab',
        'jabatan',
        'alamat',
        'no_telp',
        'email',
        'logo',
        'tanda_tangan',
    ];



    #relasi
    public function judulKerjasama(): HasMany
    {
        return $this->hasMany(Judul_Kerjasama::class, 'mitra_id');
    }

    public function pihakBersangkutan(): HasMany
    {
        return $this->hasMany(Pihak_Bersangkutan::class, 'mitra_id');
    }

}

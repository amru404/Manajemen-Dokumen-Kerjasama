<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mitra extends Model
{
    use SoftDeletes;

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

    public function documentsAsPihak1(): HasMany
    {
        return $this->hasMany(Document::class, 'pihak_1_id');
    }

    public function documentsAsPihak2(): HasMany
    {
        return $this->hasMany(Document::class, 'pihak_2_id');
    }

    public function hasDocumentRelations(): bool
    {
        return $this->documentsAsPihak1()->exists() || $this->documentsAsPihak2()->exists();
    }
}

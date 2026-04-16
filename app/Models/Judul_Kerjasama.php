<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Judul_Kerjasama extends Model
{
    protected $table = 'judul_kerjasamas';

    protected $fillable = [
        'judul',
        'mitra_id',    
    ];


    #relasi belongsTo
    public function mitra(): BelongsTo
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }


    #relasi has many
    public function pihakBersangkutan(): HasMany
    {
        return $this->hasMany(Pihak_Bersangkutan::class, 'judul_id');
    }

    public function Bersangkutan(): HasMany
    {
        return $this->hasMany(Pihak_Bersangkutan::class, 'judul_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(\App\Models\Document::class, 'judul_id');
    }
} 

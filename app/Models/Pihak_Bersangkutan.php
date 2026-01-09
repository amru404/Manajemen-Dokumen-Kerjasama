<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pihak_Bersangkutan extends Model
{
    protected $table = 'pihak_bersangkutans';

    protected $fillable = [
        'peran',
        'mitra_id',    
        'judul_id',    
    ];

    public function mitra(): BelongsTo
    {
        return $this->belongsTo(Mitra::class, 'id');
    }


    public function judul(): BelongsTo
    {
        return $this->belongsTo(Mitra::class, 'id');
    }

}

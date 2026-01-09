<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mou extends Model
{
     protected $table = 'mous';

    protected $fillable = [
        'judul_id',
        'tgl_mulai',    
        'tgl_selesai',    
        'no_mou',    
        'file',    
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'user_id',
        'judul_id',
        'pihak_1_id',
        'pihak_2_id',
        'nomor_document',
        'content_html',
        'file_path',
        'source',
        'status',
        'start_date',
        'end_date',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function judul()
    {
        return $this->belongsTo(Judul_Kerjasama::class, 'judul_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pihak1()
    {
        return $this->belongsTo(Mitra::class, 'pihak_1_id');
    }

    public function pihak2()
    {
        return $this->belongsTo(Mitra::class, 'pihak_2_id');
    }

   public static function checkExpired()
    {
        $today = now();

        self::where('end_date', '<', $today)
            ->update([
                'status' => 'expired'
            ]);

        self::whereBetween('end_date', [$today, $today->copy()->addDays(30)])
            ->where('status', '!=', 'expired')
            ->update([
                'status' => 'akan_expired'
            ]);
    }

     public function documentActivities()
    {
        return $this->hasMany(DocumentActivity::class);
    }
}


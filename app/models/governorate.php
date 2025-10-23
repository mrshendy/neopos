<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class governorate extends Model
{
    use SoftDeletes, HasTranslations;

    protected $table = 'governorate'; // ✅ مفرد
    public $translatable = ['name'];

    protected $fillable = [
        'name',            // JSON
        'id_country',
        'status',          // لو موجود
        'user_add',
        'user_update',
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (is_null($m->user_add))   $m->user_add   = auth()->id() ?? 0;
        });
        static::updating(function ($m) {
            $m->user_update = auth()->id() ?? 0;
        });
    }

    public function country()
    {
        return $this->belongsTo(country::class, 'id_country');
    }

    public function cities()
    {
        return $this->hasMany(city::class, 'id_governoratees');
    }
}

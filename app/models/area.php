<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class area extends Model
{
    use SoftDeletes, HasTranslations;

    protected $table = 'area';
    public $translatable = ['name'];

    protected $fillable = [
        'name',               // JSON
        'id_country',
        'id_governoratees',
        'id_city',
        'status',
        'user_add',
        'user_update',
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (is_null($m->user_add)) $m->user_add = auth()->id() ?? 0;
        });
        static::updating(function ($m) {
            $m->user_update = auth()->id() ?? 0;
        });
    }

    // علاقات
    public function country()
    {
        return $this->belongsTo(country::class, 'id_country');
    }

    public function governoratees()
    {
        return $this->belongsTo(governorate::class, 'id_governoratees');
    }

    public function city()
    {
        return $this->belongsTo(city::class, 'id_city');
    }
}

<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class country extends Model
{
    use SoftDeletes, HasTranslations;

    protected $table = 'country'; // ✅ مطابق لرسائل الخطأ
    public $translatable = ['name'];

    protected $fillable = [
        'name',        // JSON: ['ar'=>..., 'en'=>...]
        'code',        // لو موجود
        'status',      // لو موجود
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

    // علاقات
    public function governorates()
    {
        return $this->hasMany(governorate::class, 'id_country');
    }
}

<?php

namespace App\models\currencies;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class currencies extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'currencies';

    protected $fillable = [
        'code','name','symbol','decimal_places','thousand_separator','decimal_separator',
        'exchange_rate','is_default','status','notes','created_by','updated_by'
    ];

    public $translatable = ['name','notes'];

    protected $casts = [
        'is_default' => 'boolean',
        'exchange_rate' => 'decimal:6',
    ];

    // علاقات خفيفة (اختيارية) مع المستخدمين إن وجدت جداول users
    public function creator(){ return $this->belongsTo(\App\Models\User::class, 'created_by'); }
    public function updater(){ return $this->belongsTo(\App\Models\User::class, 'updated_by'); }

    // Scopes
    public function scopeActive($q){ return $q->where('status','active'); }
    public function scopeSearch($q, $term){
        if(!$term) return $q;
        $locale = app()->getLocale();
        // البحث في code + الاسم باللغة الحالية داخل JSON
        return $q->where(function($w) use($term,$locale){
            $w->where('code','like',"%$term%")
              ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"$locale\"')) like ?", ["%$term%"]);
        });
    }
}

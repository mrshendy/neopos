<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class governorate extends Model
{
    use HasTranslations, SoftDeletes;

    protected $table = 'governorate';   // صرّحنا باسم الجدول (بدل governorates)
    protected $primaryKey = 'id';
    public $timestamps = true;

    public $translatable = ['name'];

    protected $fillable = [
        'name',          // JSON: {"ar": "...", "en": "..."}
        'id_country',
        'status',
        'user_add',
        'user_update',
    ];

    protected $casts = [
        'name'       => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public const STATUS_ACTIVE   = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public function country(): BelongsTo
    {
        return $this->belongsTo(country::class, 'id_country');
    }

    public function scopeActive($q)
    {
        return $q->where('status', self::STATUS_ACTIVE);
    }

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            if (auth()->check() && empty($m->user_add)) {
                $m->user_add = auth()->id();
            }
        });

        static::updating(function (self $m) {
            if (auth()->check()) {
                $m->user_update = auth()->id();
            }
        });
    }
}

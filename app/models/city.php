<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class city extends Model
{
    use HasTranslations, SoftDeletes;

    /** اسم الجدول صريح لأن Eloquent يتوقع "cities" افتراضياً */
    protected $table = 'city';

    protected $primaryKey = 'id';
    public $timestamps = true;

    /** حقل قابل للترجمة وفق Spatie */
    public $translatable = ['name'];

    /** الحقول القابلة للملء */
    protected $fillable = [
        'name',               // JSON: {"ar": "...", "en": "..."}
        'id_country',
        'id_governoratees',
        'status',
        'user_add',
        'user_update',
    ];

    /** التحويلات */
    protected $casts = [
        'name'       => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /** ثوابت للحالة (اختياري) */
    public const STATUS_ACTIVE   = 'active';
    public const STATUS_INACTIVE = 'inactive';

    /* ======================= العلاقات ======================= */

    public function country(): BelongsTo
    {
        return $this->belongsTo(country::class, 'id_country');
    }

    /** ملاحظة: اسم العلاقة كما تستعمله في الشفرة لديك governoratees */
    public function governoratees(): BelongsTo
    {
        return $this->belongsTo(governorate::class, 'id_governoratees');
    }

    /* ======================= سكوبات مفيدة ======================= */

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /* ======================= تتبّع المستخدم (اختياري) ======================= */

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (auth()->check() && empty($model->user_add)) {
                $model->user_add = auth()->id();
            }
        });

        static::updating(function (self $model) {
            if (auth()->check()) {
                $model->user_update = auth()->id();
            }
        });
    }
}

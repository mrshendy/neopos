<?php

namespace App\models\product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class unit extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'units';

    // name فقط هو المتعدد اللغات (حسب جدولك الحالي)
    public $translatable = ['name'];

    protected $fillable = [
        'code',
        'name',
        'abbreviation',       // ملاحظة: عمود عادي (VARCHAR) وليس JSON
        'kind',               // 'major' | 'minor'
        'parent_id',          // يربط الصغرى بالكبرى
        'ratio_to_parent',    // نسبة التحويل للصغرى
        'is_default_minor',   // هل هي الصغرى الافتراضية؟
        'status',             // 'active' | 'inactive'
    ];

    protected $casts = [
        'is_default_minor' => 'boolean',
        'ratio_to_parent'  => 'decimal:6', // أو 'float' لو تفضّل
    ];

    /* ================= علاقات ================= */

    // منتجات ترتبط بوحدة (لو عندك FK في products يشير للوحدة)
    public function products()
    {
        return $this->hasMany(product::class);
    }

    // الوحدة الكبرى (لو هذه صغرى)
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    // الوحدات الصغرى التابعة لهذه الكبرى
    public function minors()
    {
        return $this->hasMany(self::class, 'parent_id')
                    ->where('kind', 'minor');
    }

    /* ================= سكوبات مفيدة ================= */

    public function scopeMajors($q)
    {
        return $q->where('kind', 'major');
    }

    public function scopeMinors($q)
    {
        return $q->where('kind', 'minor');
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }

    /* =============== هيلبرز اختيارية =============== */

    // هل هذه وحدة كبرى؟
    public function getIsMajorAttribute(): bool
    {
        return $this->kind === 'major';
    }

    // هل هذه وحدة صغرى؟
    public function getIsMinorAttribute(): bool
    {
        return $this->kind === 'minor';
    }
}

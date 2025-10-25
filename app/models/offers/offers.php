<?php

namespace App\models\offers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Carbon\Carbon;

/**
 * موديل العروض (offers)
 */
class offers extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'offers';

    protected $fillable = [
        'code', 'name', 'description',
        'type', 'discount_value', 'x_qty', 'y_qty', 'bundle_price',
        'max_discount_per_order', 'is_stackable', 'priority', 'policy', 'status',
        'start_at', 'end_at', 'days_of_week', 'hours_from', 'hours_to', 'sales_channel',
    ];

    public $translatable = ['name', 'description'];

    protected $casts = [
        'is_stackable' => 'boolean',
        'days_of_week' => 'array',
        'start_at'     => 'datetime',
        'end_at'       => 'datetime',
    ];

    /** علاقات */
    public function branches()
    {
        // عدّل المسار لو اسم موديل الفروع مختلف عندك
        return $this->belongsToMany(\App\models\General\Branch::class, 'offer_branches');
    }

    public function products()
    {
        // يوجد min_qty على Pivot
        return $this->belongsToMany(\App\models\Products\Product::class, 'offer_products')
                    ->withPivot('min_qty');
    }

    public function categories()
    {
        return $this->belongsToMany(\App\models\Products\Category::class, 'offer_categories');
    }

    /** Scopes */
    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }

    public function scopeWithinPeriod($q, ?Carbon $when = null)
    {
        $when = $when ?: now();
        return $q->where(function ($qq) use ($when) {
            $qq->whereNull('start_at')->orWhere('start_at', '<=', $when);
        })->where(function ($qq) use ($when) {
            $qq->whereNull('end_at')->orWhere('end_at', '>=', $when);
        });
    }

    public function scopeForBranch($q, $branchId)
    {
        return $q->whereHas('branches', fn($b) => $b->where('branches.id', $branchId));
    }

    public function scopeForProduct($q, $productId)
    {
        return $q->whereHas('products', fn($p) => $p->where('products.id', $productId));
    }

    public function scopeForCategory($q, $categoryId)
    {
        return $q->whereHas('categories', fn($c) => $c->where('categories.id', $categoryId));
    }

    public function scopeForChannel($q, ?string $channel)
    {
        return $q->when($channel, fn($qq) => $qq->where(function ($w) use ($channel) {
            $w->whereNull('sales_channel')->orWhere('sales_channel', $channel);
        }));
    }

    /** Helpers */
    public function getNameCurrentLangAttribute(): string
    {
        return $this->getTranslation('name', app()->getLocale());
    }

    public function isActiveNow(?int $branchId = null, ?string $channel = null, ?Carbon $at = null): bool
    {
        if ($this->status !== 'active') return false;

        $at = $at ?: now();

        // فترة زمنية
        if (($this->start_at && $at->lt($this->start_at)) || ($this->end_at && $at->gt($this->end_at))) {
            return false;
        }

        // أيام الأسبوع
        if (is_array($this->days_of_week) && count($this->days_of_week)) {
            // Carbon: Monday=1 .. Sunday=7 (متطابق مع تصميمنا)
            if (!in_array($at->dayOfWeekIso, $this->days_of_week, true)) return false;
        }

        // ساعات اليوم
        if ($this->hours_from && $this->hours_to) {
            $t = $at->format('H:i');
            if (!($t >= $this->hours_from && $t <= $this->hours_to)) return false;
        }

        // القناة
        if ($this->sales_channel && $channel && $this->sales_channel !== $channel) return false;

        // الفرع
        if ($branchId && !$this->branches()->where('branches.id', $branchId)->exists()) return false;

        return true;
    }

    /** ترقيم تلقائي قبل الإنشاء */
    protected static function booted()
    {
        static::creating(function (self $model) {
            if (empty($model->code)) {
                $model->code = numbersequences::next('offers', 'PROMO-' . now()->year . '-');
            }
        });
    }
}

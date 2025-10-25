<?php

namespace App\models\offers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Carbon\Carbon;

/**
 * موديل الكوبونات (coupons)
 */
class coupons extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'coupons';

    protected $fillable = [
        'code', 'name', 'description',
        'type', 'discount_value',
        'max_uses_per_customer', 'max_total_uses', 'used_count',
        'is_stackable', 'status', 'start_at', 'end_at',
    ];

    public $translatable = ['name', 'description'];

    protected $casts = [
        'is_stackable' => 'boolean',
        'start_at'     => 'datetime',
        'end_at'       => 'datetime',
    ];

    /** علاقات */
    public function branches()
    {
        return $this->belongsToMany(\App\models\General\Branch::class, 'coupon_branches');
    }

    public function customers()
    {
        return $this->belongsToMany(\App\models\Customers\Customer::class, 'coupon_customers');
    }

    public function usages()
    {
        return $this->hasMany(couponusages::class, 'coupon_id');
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

    /** Helpers */
    public function getNameCurrentLangAttribute(): string
    {
        return $this->getTranslation('name', app()->getLocale());
    }

    public function isActiveNow(?int $branchId = null, ?Carbon $at = null): bool
    {
        if ($this->status !== 'active') return false;

        $at = $at ?: now();

        if (($this->start_at && $at->lt($this->start_at)) || ($this->end_at && $at->gt($this->end_at))) {
            return false;
        }

        if ($branchId && !$this->branches()->where('branches.id', $branchId)->exists()) return false;

        return true;
    }

    /**
     * تحقق إمكانية الاستخدام لعميل/فرع
     */
    public function canBeApplied(?int $customerId = null, ?int $branchId = null, ?Carbon $at = null): array
    {
        if (!$this->isActiveNow($branchId, $at)) {
            return [false, 'expired_or_inactive'];
        }

        if ($this->max_total_uses !== null && $this->used_count >= $this->max_total_uses) {
            return [false, 'global_limit_reached'];
        }

        if ($customerId) {
            $count = $this->usages()->where('customer_id', $customerId)->count();
            if ($count >= $this->max_uses_per_customer) {
                return [false, 'customer_limit_reached'];
            }
        }

        return [true, null];
    }

    /**
     * تسجيل استخدام الكوبون (يزوّد العداد + ينشئ سجل usage)
     */
    public function logUsage(?int $customerId, ?int $branchId, ?int $orderId, float $amountDiscounted, ?Carbon $at = null): void
    {
        $at = $at ?: now();

        $this->increment('used_count');

        $this->usages()->create([
            'customer_id'       => $customerId,
            'branch_id'         => $branchId,
            'order_id'          => $orderId,
            'amount_discounted' => $amountDiscounted,
            'used_at'           => $at,
        ]);
    }

    /** ترقيم تلقائي */
    protected static function booted()
    {
        static::creating(function (self $model) {
            if (empty($model->code)) {
                $model->code = numbersequences::next('coupons', 'CP-');
            }
        });
    }
}

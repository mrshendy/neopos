<?php

namespace App\models\offers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * سجل استخدامات الكوبونات (لا يحتاج SoftDeletes عادةً)
 */
class couponusages extends Model
{
    use HasFactory;

    protected $table = 'coupon_usages';

    protected $fillable = [
        'coupon_id', 'customer_id', 'branch_id', 'order_id',
        'amount_discounted', 'used_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];

    public function coupon()
    {
        return $this->belongsTo(coupons::class, 'coupon_id');
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customers\Customer::class, 'customer_id');
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\General\Branch::class, 'branch_id');
    }
}

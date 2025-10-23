<?php

namespace App\Models\pos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Pos extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $table = 'pos';

    // ✅ أعمدة مطابقة للجدول (حسب الميجريشن/الإنسيرت الفعلي)
    protected $fillable = [
        'pos_no',        // كان عندك sale_no
        'pos_date',      // كان عندك sale_date
        'warehouse_id',
        'customer_id',
        'status',        // enum: draft/approved/posted/cancelled
        'notes',
        'subtotal',
        'discount',
        'tax',
        'grand_total',
        'user_id',
        // مفيش delivery_date في الجدول الحالي، سيبه لما تضيف العمود في الميجريشن
    ];

    public $translatable = ['notes'];

    // ✅ Casts مفيدة
    protected $casts = [
        'pos_date' => 'date',
        'subtotal' => 'decimal:4',
        'discount' => 'decimal:4',
        'tax' => 'decimal:4',
        'grand_total' => 'decimal:4',
    ];

    // ✅ Normalize IDs لمنع كسر FK (0/"" => null)
    public function setCustomerIdAttribute($val)
    {
        $this->attributes['customer_id'] =
            (is_null($val) || $val === '' || (int) $val === 0) ? null : (int) $val;
    }

    public function setWarehouseIdAttribute($val)
    {
        $this->attributes['warehouse_id'] =
            (is_null($val) || $val === '' || (int) $val === 0) ? null : (int) $val;
    }

    // ===== العلاقات =====
    public function lines()
    {
        return $this->hasMany(PosLine::class, 'pos_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(\App\models\inventory\warehouse::class, 'warehouse_id');
    }

    public function customer()
    {
        return $this->belongsTo(\App\models\customer\customer::class, 'customer_id');
    }

    public function user()
    {
        // لو مشروعك قديم وما زال App\User استخدمه بدلاً من السطر التالي
        return $this->belongsTo(\App\User::class, 'user_id');
        // return $this->belongsTo(\App\User::class, 'user_id');
    }

    // (اختياري) Helper لتقييد القيم المسموحة في status
    public const STATUS_DRAFT = 'draft';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_POSTED = 'posted';

    public const STATUS_CANCELLED = 'cancelled'; // لاحظ LL

    public static function allowedStatuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_APPROVED,
            self::STATUS_POSTED,
            self::STATUS_CANCELLED,
        ];
    }

    public function setStatusAttribute($val)
    {
        $val = strtolower((string) $val);
        // طَبّق شكل enum الموجود في الجدول بالضبط
        if ($val === 'canceled') {
            $val = 'cancelled';
        }
        $this->attributes['status'] = in_array($val, self::allowedStatuses(), true)
            ? $val
            : self::STATUS_DRAFT;
    }
}

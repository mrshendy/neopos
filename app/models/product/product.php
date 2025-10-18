<?php

namespace App\models\product;

use App\models\inventory\batch;
use App\models\inventory\reorder_level;
use App\models\inventory\serial;
use App\models\inventory\stock_transaction_line;
use App\models\unit\unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// علاقات خارجية
use Illuminate\Database\Eloquent\Model;
// مخازن (بحسب كودك الحالي)
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\Translatable\HasTranslations;

class product extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $table = 'products';

    protected $guarded = [];

    /** الحقول القابلة للترجمة */
    public $translatable = ['name', 'description'];

    /** التحويلات (Casts) */
    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'units_matrix' => 'array',   // هيكل: minor/middle/major => unit_id/cost/price/factor
        'expiry_enabled' => 'boolean',
        'expiry_value' => 'integer',
        'expiry_weekdays' => 'array',  
        'category_id' => 'integer',
        'supplier_id'    => 'integer', // فعّل لو عندك العمود
    ];

    /* ========= العلاقات الأساسية ========= */

    public function category(): BelongsTo
    {
        return $this->belongsTo(category::class);
    }

    /**
     * الوحدة الافتراضية للمنتج (لو عندك عمود unit_id على الجدول).
     * إن ما كنت تستخدمه، سيظل الكود آمن.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(unit::class, 'unit_id');
    }

    public function transactions()
    {
        return $this->hasMany(stock_transaction_line::class, 'product_id');
    }

    public function batches()
    {
        return $this->hasMany(batch::class);
    }

    public function serials()
    {
        return $this->hasMany(serial::class);
    }

    public function reorderLevel()
    {
        return $this->hasOne(reorder_level::class);
    }

    /* ========= أدوات الوحدات من units_matrix ========= */

    /** يعيد كائن وحدة البيع */
    public function getSaleUnitAttribute(): ?unit
    {
        $key = $this->sale_unit_key; // minor|middle|major
        $unitId = data_get($this->units_matrix, "{$key}.unit_id");

        return $unitId ? unit::find($unitId) : null;
    }

    /** يعيد كائن وحدة الشراء */
    public function getPurchaseUnitAttribute(): ?unit
    {
        $key = $this->purchase_unit_key; // minor|middle|major
        $unitId = data_get($this->units_matrix, "{$key}.unit_id");

        return $unitId ? unit::find($unitId) : null;
    }

    /** معرف الوحدة الصغرى/الوسطى/الكبرى للمساعدة السريعة */
    public function getMinorUnitIdAttribute(): ?int
    {
        return data_get($this->units_matrix, 'minor.unit_id');
    }

    public function getMiddleUnitIdAttribute(): ?int
    {
        return data_get($this->units_matrix, 'middle.unit_id');
    }

    public function getMajorUnitIdAttribute(): ?int
    {
        return data_get($this->units_matrix, 'major.unit_id');
    }

    /* ========= Scopes للتصفية ========= */

    /** بحث في sku/barcode/الاسمين */
    public function scopeSearch($q, ?string $term)
    {
        if (! $term) {
            return $q;
        }

        $like = '%'.$term.'%';

        return $q->where(function ($qq) use ($like) {
            $qq->where('sku', 'like', $like)
                ->orWhere('barcode', 'like', $like)
                ->orWhere('name->ar', 'like', $like)
                ->orWhere('name->en', 'like', $like);
        });
    }

    /** حالة نشط/غير نشط */
    public function scopeStatus($q, ?string $status)
    {
        if (! $status) {
            return $q;
        }

        return $q->where('status', $status);
    }

    /** ضمن قسم محدد */
    public function scopeInCategory($q, $categoryId)
    {
        if (! $categoryId) {
            return $q;
        }

        return $q->where('category_id', $categoryId);
    }

    /* ========= رصيد لحظي مجمّع (بالصغرى) =========
       مطابق لمنطقتك السابقة، مع تحسينات طفيفة بالـ DB::raw
    */
    public function scopeWithCurrentQty($q)
    {
        return $q
            ->withSum(['transactions as qty_in_minor' => function ($qq) {
                $qq->whereHas('transaction', function ($t) {
                    $t->whereIn('type', ['in', 'transfer', 'adjustment']);
                })
                    ->select(DB::raw("
                        COALESCE(SUM(
                            CASE
                                WHEN stock_transactions.type IN ('in','transfer') THEN qty_minor
                                WHEN stock_transactions.type = 'adjustment' AND qty_minor > 0 THEN qty_minor
                                ELSE 0
                            END
                        ), 0)
                    "));
            }], 'qty_minor')
            ->withSum(['transactions as qty_out_minor' => function ($qq) {
                $qq->whereHas('transaction', function ($t) {
                    $t->whereIn('type', ['out', 'transfer', 'adjustment']);
                })
                    ->select(DB::raw("
                        COALESCE(SUM(
                            CASE
                                WHEN stock_transactions.type IN ('out','transfer') THEN qty_minor
                                WHEN stock_transactions.type = 'adjustment' AND qty_minor < 0 THEN ABS(qty_minor)
                                ELSE 0
                            END
                        ), 0)
                    "));
            }], 'qty_minor');
    }

    /** إرجاع صافي الرصيد اللحظي بالوحدة الصغرى */
    public function getCurrentQtyMinorAttribute(): float
    {
        $in = $this->qty_in_minor ?? 0;
        $out = $this->qty_out_minor ?? 0;

        return (float) $in - (float) $out;
    }

    /* ========= ضمان بنية units_matrix عند الحفظ ========= */
    public function setUnitsMatrixAttribute($value): void
    {
        $default = [
            'minor' => ['unit_id' => null, 'cost' => null, 'price' => null, 'factor' => 1],
            'middle' => ['unit_id' => null, 'cost' => null, 'price' => null, 'factor' => 1],
            'major' => ['unit_id' => null, 'cost' => null, 'price' => null, 'factor' => 1],
        ];

        $value = is_array($value) ? $value : [];
        foreach (['minor', 'middle', 'major'] as $k) {
            $value[$k] = array_merge($default[$k], $value[$k] ?? []);
        }

        $this->attributes['units_matrix'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}

<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class warehouse
 *
 * @property int $id
 * @property array|null $name       // ['ar' => '...', 'en' => '...']
 * @property int|null $branch_id
 * @property string $status         // active | inactive
 */
class warehouse extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'warehouses';

    // استخدم fillable فقط (وأزلنا guarded لتجنب التعارض)
    protected $fillable = [
        'name',
        'branch_id',
        'status',
    ];

    protected $casts = [
        'name' => 'array',
    ];

    /** ===== Scopes ===== */

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }

    public function scopeForBranch($q, ?int $branchId)
    {
        return $branchId ? $q->where('branch_id', $branchId) : $q;
    }

    /** ===== Relations ===== */

    /** معاملات مخزنية كمصدر (تحويل/صرف) */
    public function stockTransactionsSrc(): HasMany
    {
        return $this->hasMany(stock_transaction::class, 'src_warehouse_id');
    }

    /** معاملات مخزنية كوجهة (تحويل/إدخال) */
    public function stockTransactionsDst(): HasMany
    {
        return $this->hasMany(stock_transaction::class, 'dst_warehouse_id');
    }

    /** الفرع التابع له المخزن */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\models\general\branch::class, 'branch_id');
    }

    /** ===== Helpers ===== */

    /**
     * كمية المنتج (بالوحدة الصغرى) داخل هذا المخزن الآن.
     * يحتسب:
     * - الوارد: type in + transfer كوجهة (dst)
     * - الصادر: type out + transfer كمصدر (src)
     * - التسويات: adjustment (+/-) على نفس المخزن كمصدر
     */
    public function currentQtyMinorForProduct(int $productId): float
    {
        // إجمالي الوارد (in + transfer) إلى هذا المخزن
        $in = stock_transaction_line::where('product_id', $productId)
            ->whereHas('transaction', function ($t) {
                $t->where(function ($w) {
                    $w->where('type', 'in')
                      ->orWhere('type', 'transfer');
                })->where('dst_warehouse_id', $this->id);
            })
            ->sum('qty_minor');

        // إجمالي الصادر (out + transfer) من هذا المخزن
        $out = stock_transaction_line::where('product_id', $productId)
            ->whereHas('transaction', function ($t) {
                $t->where(function ($w) {
                    $w->where('type', 'out')
                      ->orWhere('type', 'transfer');
                })->where('src_warehouse_id', $this->id);
            })
            ->sum('qty_minor');

        // التسويات الموجبة على هذا المخزن
        $adjPlus = stock_transaction_line::where('product_id', $productId)
            ->whereHas('transaction', function ($t) {
                $t->where('type', 'adjustment')
                  ->where('src_warehouse_id', $this->id);
            })
            ->where('qty_minor', '>', 0)
            ->sum('qty_minor');

        // التسويات السالبة على هذا المخزن (نجمع القيمة المطلقة)
        $adjMinus = stock_transaction_line::where('product_id', $productId)
            ->whereHas('transaction', function ($t) {
                $t->where('type', 'adjustment')
                  ->where('src_warehouse_id', $this->id);
            })
            ->where('qty_minor', '<', 0)
            ->sum(\DB::raw('ABS(qty_minor)'));

        return (float) $in - (float) $out + (float) $adjPlus - (float) $adjMinus;
    }
}

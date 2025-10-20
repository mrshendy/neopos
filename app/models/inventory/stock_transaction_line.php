<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\models\product\product;
use App\models\unit\unit;

class stock_transaction_line extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stock_transaction_lines';

    protected $fillable = [
        'stock_transaction_id',
        'product_id',
        'unit_id',
        'uom',          // نص الوحدة وقت الحركة (مثلاً BOX / PCS)
        'qty',          // كمية موجبة أو سالبة حسب نوع الحركة
        'reason',       // سبب الحركة (اختياري)
        'expiry_date',  // تاريخ الصلاحية (اختياري)
        'batch_no',     // رقم التشغيلة (اختياري)
        'batch_id',     // لو عندك جدول batches (اختياري)
        'serial_id',    // لو عندك جدول serials  (اختياري)
    ];

    protected $casts = [
        'qty'         => 'decimal:4',
        'expiry_date' => 'date',
    ];

    /** الحركة (الهيدر) */
    public function transaction()
    {
        return $this->belongsTo(stock_transaction::class, 'stock_transaction_id');
    }

    /** الصنف */
    public function product()
    {
        return $this->belongsTo(product::class, 'product_id');
    }

    /** الوحدة */
    public function unit()
    {
        return $this->belongsTo(unit::class, 'unit_id');
    }
}

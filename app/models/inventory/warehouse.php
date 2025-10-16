<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class warehouse extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'warehouses';
    protected $guarded = [];

    public function scopeActive($q){ return $q->where('status','active'); }

    public function stockTransactionsSrc(){ return $this->hasMany(stock_transaction::class,'src_warehouse_id'); }
    public function stockTransactionsDst(){ return $this->hasMany(stock_transaction::class,'dst_warehouse_id'); }

    /* كمية منتج ما داخل هذا المخزن (بالصغرى) */
    public function currentQtyMinorForProduct(int $productId): float
    {
        $in = stock_transaction_line::where('product_id',$productId)
            ->whereHas('transaction', fn($t)=>$t->where(function($w){
                $w->where('type','in')->orWhere('type','transfer');
            })->where('dst_warehouse_id',$this->id))
            ->sum('qty_minor');

        $out = stock_transaction_line::where('product_id',$productId)
            ->whereHas('transaction', fn($t)=>$t->where(function($w){
                $w->where('type','out')->orWhere('type','transfer');
            })->where('src_warehouse_id',$this->id))
            ->sum('qty_minor');

        // التسويات:
        $adjPlus  = stock_transaction_line::where('product_id',$productId)
            ->whereHas('transaction', fn($t)=>$t->where('type','adjustment')->where('src_warehouse_id',$this->id))
            ->where('qty_minor','>',0)->sum('qty_minor');

        $adjMinus = stock_transaction_line::where('product_id',$productId)
            ->whereHas('transaction', fn($t)=>$t->where('type','adjustment')->where('src_warehouse_id',$this->id))
            ->where('qty_minor','<',0)->sum(\DB::raw('abs(qty_minor)'));

        return (float)$in - (float)$out + (float)$adjPlus - (float)$adjMinus;
    }
}

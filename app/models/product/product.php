<?php

namespace App\models\product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class product extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'products'; // غيّره لو مختلف
    protected $guarded = [];
    public $translatable = ['name','description'];

    /* ===== علاقات أساسية ===== */
    public function category()   { return $this->belongsTo(category::class); }
    public function unit()       { return $this->belongsTo(unit::class, 'unit_id'); } // الوحدة الافتراضية للمنتج (غالبًا كبرى)
    public function transactions(){ return $this->hasMany(\App\models\inventory\stock_transaction_line::class, 'product_id'); }
    public function batches()    { return $this->hasMany(\App\models\inventory\batch::class); }
    public function serials()    { return $this->hasMany(\App\models\inventory\serial::class); }
    public function reorderLevel(){ return $this->hasOne(\App\models\inventory\reorder_level::class); }

    /* ===== رصيد لحظي مجمّع (بالصغرى) =====
       يمكن استعمال where('warehouse_id', X) عبر Scopes على الـlines */
    public function scopeWithCurrentQty($q)
    {
        return $q->withSum(['transactions as qty_in_minor' => function($qq){
                    $qq->whereHas('transaction', fn($t)=>$t->whereIn('type',['in','transfer','adjustment']))
                       ->select(\DB::raw("coalesce(sum(case 
                           when stock_transactions.type in ('in','transfer') then qty_minor 
                           when stock_transactions.type='adjustment' and qty_minor>0 then qty_minor
                           else 0 end),0)"));
                }], 'qty_minor')
                ->withSum(['transactions as qty_out_minor' => function($qq){
                    $qq->whereHas('transaction', fn($t)=>$t->whereIn('type',['out','transfer','adjustment']))
                       ->select(\DB::raw("coalesce(sum(case 
                           when stock_transactions.type in ('out','transfer') then qty_minor 
                           when stock_transactions.type='adjustment' and qty_minor<0 then abs(qty_minor)
                           else 0 end),0)"));
                }], 'qty_minor');
    }

    /* كمية حالية (صغرى) على مستوى كل النظام (تجميعي) */
    public function getCurrentQtyMinorAttribute()
    {
        $in  = $this->qty_in_minor ?? 0;
        $out = $this->qty_out_minor ?? 0;
        return (float)$in - (float)$out;
    }
}

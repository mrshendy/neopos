<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class stock_transaction extends Model
{
    use HasFactory;

    protected $table = 'stock_transactions';
    protected $guarded = [];
    protected $casts = ['transacted_at' => 'datetime'];

    public function lines()         { return $this->hasMany(stock_transaction_line::class, 'transaction_id'); }
    public function srcWarehouse()  { return $this->belongsTo(warehouse::class, 'src_warehouse_id'); }
    public function dstWarehouse()  { return $this->belongsTo(warehouse::class, 'dst_warehouse_id'); }

    /* سكوبات نوع الحركة */
    public function scopeIn($q)       { return $q->where('type','in'); }
    public function scopeOut($q)      { return $q->where('type','out'); }
    public function scopeTransfer($q) { return $q->where('type','transfer'); }
    public function scopeAdjustment($q){ return $q->where('type','adjustment'); }
}

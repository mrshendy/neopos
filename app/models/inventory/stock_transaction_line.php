<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class stock_transaction_line extends Model
{
    use HasFactory;

    protected $table = 'stock_transaction_lines';
    protected $guarded = [];
    protected $casts = [
        'qty' => 'decimal:6',
        'qty_minor' => 'decimal:6',
        'ratio_to_minor' => 'decimal:6',
    ];

    public function transaction(){ return $this->belongsTo(stock_transaction::class, 'transaction_id'); }
    public function product()    { return $this->belongsTo(\App\models\product\product::class, 'product_id'); }
    public function unit()       { return $this->belongsTo(\App\models\product\unit::class, 'unit_id'); }
}

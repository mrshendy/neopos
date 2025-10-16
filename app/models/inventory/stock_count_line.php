<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class stock_count_line extends Model
{
    use HasFactory;

    protected $table = 'stock_count_lines';
    protected $guarded = [];
    protected $casts = ['qty_minor'=>'decimal:6'];

    public function count(){ return $this->belongsTo(stock_count::class, 'count_id'); }
    public function product(){ return $this->belongsTo(\App\models\product\product::class); }
    public function unit(){ return $this->belongsTo(\App\models\product\unit::class); }
}

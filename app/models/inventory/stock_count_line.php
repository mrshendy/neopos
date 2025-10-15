<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class stock_count_line extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stock_count_lines';

    protected $fillable = ['stock_count_id', 'item_id', 'batch_id', 'system_qty', 'counted_qty', 'difference_qty'];

    public function count()
    {
        return $this->belongsTo(stock_count::class, 'stock_count_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\models\product\product::class, 'product_id');
    }

    public function batch()
    {
        return $this->belongsTo(batch::class, 'batch_id');
    }
}

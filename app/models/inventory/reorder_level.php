<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class reorder_level extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reorder_levels';

    protected $fillable = ['item_id', 'warehouse_id', 'min_qty'];

    public function product()
    {
        return $this->belongsTo(\App\models\product\product::class, 'product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(warehouse::class, 'warehouse_id');
    }
}

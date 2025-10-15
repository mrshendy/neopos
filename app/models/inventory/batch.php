<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class batch extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'batches';

    protected $fillable = ['item_id', 'warehouse_id', 'batch_no', 'mfg_date', 'exp_date', 'qty_on_hand'];

    public function product()
    {
        return $this->belongsTo(\App\models\product\product::class, 'product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(warehouse::class, 'warehouse_id');
    }
}

<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class serial extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'serials';

    protected $fillable = ['item_id', 'warehouse_id', 'batch_id', 'serial_no', 'status'];

    public function product()
    {
        return $this->belongsTo(\App\models\product\product::class, 'product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(warehouse::class, 'warehouse_id');
    }

    public function batch()
    {
        return $this->belongsTo(batch::class, 'batch_id');
    }
}

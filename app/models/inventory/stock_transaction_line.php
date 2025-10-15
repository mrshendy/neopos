<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class stock_transaction_line extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stock_transaction_lines';

    protected $fillable = ['stock_transaction_id', 'item_id', 'batch_id', 'serial_id', 'qty', 'uom', 'reason'];

    public function trx()
    {
        return $this->belongsTo(stock_transaction::class, 'stock_transaction_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\models\product\product::class, 'product_id');
    }

    public function batch()
    {
        return $this->belongsTo(batch::class, 'batch_id');
    }

    public function serial()
    {
        return $this->belongsTo(serial::class, 'serial_id');
    }
}

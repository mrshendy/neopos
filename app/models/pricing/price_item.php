<?php

namespace App\models\pricing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class price_item extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'price_items';

    protected $fillable = [
        'price_list_id', 'product_id', 'price', 'min_qty', 'max_qty', 'valid_from', 'valid_to',
    ];

    public function product()
    {
        return $this->belongsTo(\App\models\product\product::class);
    }

    public function list()
    {
        return $this->belongsTo(price_list::class, 'price_list_id');
    }
}

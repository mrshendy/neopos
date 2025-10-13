<?php

namespace App\models\customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class customerpricing extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_pricing';

    protected $fillable = [
        'customer_id','default_price_list_id','default_discount','product_id','category_id','branch_id',
        'from_date','to_date','special_discount','min_quantity','status'
    ];

    public function customer() { return $this->belongsTo(customer::class, 'customer_id'); }
}

<?php

namespace App\Models\supplier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class supplierdiscount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'supplier_discounts';

    protected $fillable = [
        'supplier_id','type','percentage','amount','from_qty','to_qty','status'
    ];

    protected $casts = [
        'percentage'=>'decimal:2',
        'amount'=>'decimal:4',
        'from_qty'=>'integer',
        'to_qty'=>'integer',
    ];

    public function supplier(){ return $this->belongsTo(supplier::class); }
}

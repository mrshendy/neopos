<?php

namespace App\Models\pos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosLine extends Model
{
    use HasFactory;

    protected $table = 'pos_lines';

    protected $fillable = [
        'pos_id','category_id','product_id','unit_id','qty','unit_price',
        'line_total','uom','onhand','expiry_date','batch_no'
    ];

    public function pos()     { return $this->belongsTo(Pos::class, 'pos_id'); }
    public function product() { return $this->belongsTo(\App\Models\Product::class, 'product_id'); }
    public function unit()    { return $this->belongsTo(\App\Models\Unit::class, 'unit_id'); }
}

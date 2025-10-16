<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class reorder_level extends Model
{
    use HasFactory;

    protected $table = 'reorder_levels';
    protected $guarded = [];
    protected $casts = ['min_qty_minor'=>'decimal:6','max_qty_minor'=>'decimal:6'];

    public function product(){ return $this->belongsTo(\App\models\product\product::class); }
    public function warehouse(){ return $this->belongsTo(warehouse::class); }
}

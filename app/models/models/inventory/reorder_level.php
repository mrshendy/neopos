<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class reorder_level extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reorder_levels';
    protected $fillable = ['item_id','warehouse_id','min_qty'];

    public function item(){ return $this->belongsTo(item::class,'item_id'); }
    public function warehouse(){ return $this->belongsTo(warehouse::class,'warehouse_id'); }
}

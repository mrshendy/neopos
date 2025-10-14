<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class batch extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'batches';
    protected $fillable = ['item_id','warehouse_id','batch_no','mfg_date','exp_date','qty_on_hand'];

    public function item(){ return $this->belongsTo(item::class,'item_id'); }
    public function warehouse(){ return $this->belongsTo(warehouse::class,'warehouse_id'); }
}

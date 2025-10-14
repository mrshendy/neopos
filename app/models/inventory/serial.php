<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class serial extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'serials';
    protected $fillable = ['item_id','warehouse_id','batch_id','serial_no','status'];

    public function item(){ return $this->belongsTo(item::class,'item_id'); }
    public function warehouse(){ return $this->belongsTo(warehouse::class,'warehouse_id'); }
    public function batch(){ return $this->belongsTo(batch::class,'batch_id'); }
}

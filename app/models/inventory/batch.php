<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class batch extends Model
{
    use HasFactory;

    protected $table = 'batches';
    protected $guarded = [];
    protected $casts = ['expires_at'=>'date'];

    public function product(){ return $this->belongsTo(\App\models\product\product::class); }
    public function warehouse(){ return $this->belongsTo(warehouse::class); }
}

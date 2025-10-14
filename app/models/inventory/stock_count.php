<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class stock_count extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stock_counts';
    protected $fillable = ['warehouse_id','policy','started_at','approved_at','status','notes'];

    public function lines(){ return $this->hasMany(stock_count_line::class, 'stock_count_id'); }
    public function warehouse(){ return $this->belongsTo(warehouse::class,'warehouse_id'); }
}

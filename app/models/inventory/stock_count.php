<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class stock_count extends Model
{
    use HasFactory;

    protected $table = 'stock_counts';
    protected $guarded = [];
    protected $casts = ['counted_at'=>'datetime'];

    public function lines(){ return $this->hasMany(stock_count_line::class, 'count_id'); }
    public function warehouse(){ return $this->belongsTo(warehouse::class); }
}

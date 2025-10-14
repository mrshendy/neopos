<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class stock_transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stock_transactions';
    protected $fillable = [
        'trx_no','type','trx_date','warehouse_from_id','warehouse_to_id',
        'user_id','ref_type','ref_id','notes','status'
    ];

    public function lines(){ return $this->hasMany(stock_transaction_line::class, 'stock_transaction_id'); }
    public function from(){ return $this->belongsTo(warehouse::class,'warehouse_from_id'); }
    public function to(){ return $this->belongsTo(warehouse::class,'warehouse_to_id'); }
}

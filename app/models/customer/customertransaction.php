<?php

namespace App\models\customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class customertransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_transactions';

    protected $fillable = [
        'customer_id','type','reference_no','date','amount','balance_after','remarks','created_by'
    ];

    protected $casts = ['date' => 'date'];

    public function customer() { return $this->belongsTo(customer::class, 'customer_id'); }
}

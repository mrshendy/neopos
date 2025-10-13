<?php

namespace App\models\customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class customercredit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_credit';

    protected $fillable = [
        'customer_id','credit_limit','payment_terms','grace_period_days','late_fee_percent','available_credit','status'
    ];

    public function customer() { return $this->belongsTo(customer::class, 'customer_id'); }
}

<?php

namespace App\models\customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class customerapproval extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_approvals';

    protected $fillable = [
        'customer_id','approval_type','requested_by','approved_by','before_value','after_value','status','reason','approved_at'
    ];

    protected $casts = [
        'before_value' => 'array',
        'after_value'  => 'array',
        'approved_at'  => 'datetime',
    ];

    public function customer() { return $this->belongsTo(customer::class, 'customer_id'); }
}

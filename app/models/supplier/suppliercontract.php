<?php

namespace App\Models\supplier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class suppliercontract extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'supplier_contracts';

    protected $fillable = [
        'supplier_id','payment_term_id','start_date','end_date','status'
    ];

    protected $casts = [
        'start_date'=>'date',
        'end_date'=>'date',
    ];

    public function supplier()    { return $this->belongsTo(supplier::class); }
    public function paymentTerm() { return $this->belongsTo(paymentterm::class,'payment_term_id'); }
    public function products()       { return $this->hasMany(suppliercontractitem::class,'supplier_contract_id'); }
}

<?php

namespace App\models\supplier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class suppliercontractitem extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'supplier_contract_products';

    protected $fillable = [
        'supplier_contract_id','product_sku','product_name',
        'price','min_qty','max_qty'
    ];

    public $translatable = ['product_name'];

    protected $casts = [
        'product_name'=>'array',
        'price'=>'decimal:4',
        'min_qty'=>'integer',
        'max_qty'=>'integer',
    ];

    public function contract(){ return $this->belongsTo(suppliercontract::class,'supplier_contract_id'); }
}

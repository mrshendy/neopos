<?php

namespace App\Models\supplier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class paymentterm extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'payment_terms';

    protected $fillable = ['name','due_days','allow_partial','status'];

    public $translatable = ['name'];

    protected $casts = [
        'name'=>'array',
        'allow_partial'=>'boolean',
        'due_days'=>'integer',
    ];

    public function suppliers(){ return $this->hasMany(supplier::class,'payment_term_id'); }
    public function contracts(){ return $this->hasMany(suppliercontract::class,'payment_term_id'); }
}

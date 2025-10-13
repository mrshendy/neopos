<?php

namespace App\models\customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class customeraddress extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'customer_addresses';

    protected $fillable = [
        'customer_id','type','company_name','contact_person','phone','city','street','postal_code','is_default'
    ];

    public $translatable = ['company_name','contact_person'];

    public function customer() { return $this->belongsTo(customer::class, 'customer_id'); }
}

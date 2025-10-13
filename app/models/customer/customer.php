<?php

namespace App\models\customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class customer extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'customers';

    protected $fillable = [
        'code','legal_name','trade_name','type','channel',
        'country_id','government_id','city_id','area_id', // ✅ بديل region_id
        'city','phone','tax_number',
        'price_category_id','sales_rep_id',
        'credit_limit','balance','credit_status','account_status','approval_status',
        'created_by','updated_by'
    ];

    public $translatable = ['legal_name','trade_name'];

    // 🔗 علاقات جغرافية (حسب الجداول الموجودة)
    public function country()    { return $this->belongsTo(countries::class,    'country_id'); }
    // app/models/customer/customer.php
public function governorate() { return $this->belongsTo(\App\models\customer\governorate::class, 'governorate_id'); }
public function cityRel()    { return $this->belongsTo(\App\models\customer\city::class, 'city_id'); }
public function area()       { return $this->belongsTo(\App\models\customer\area::class, 'area_id'); }

    // 🔗 أخرى
    public function priceCategory() { return $this->belongsTo(pricelist::class, 'price_category_id'); }
    public function salesRep()      { return $this->belongsTo(\App\models\User::class, 'sales_rep_id'); }

    public function addresses()     { return $this->hasMany(customeraddress::class, 'customer_id'); }
    public function contacts()      { return $this->hasMany(customercontact::class, 'customer_id'); }
    public function credit()        { return $this->hasOne(customercredit::class, 'customer_id'); }
    public function pricing()       { return $this->hasMany(customerpricing::class, 'customer_id'); }
    public function documents()     { return $this->hasMany(customerdocument::class, 'customer_id'); }
    public function approvals()     { return $this->hasMany(customerapproval::class, 'customer_id'); }
    public function transactions()  { return $this->hasMany(customertransaction::class, 'customer_id'); }
    
}

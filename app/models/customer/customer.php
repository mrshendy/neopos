<?php

namespace App\models\customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use App\Models\User;

class customer extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'customers';

    protected $fillable = [
        'code',
        'legal_name',
        'trade_name',
        'type',
        'channel',
        'country_id',
        'governorate_id',
        'city_id',
        'area_id',
        'city', // نص حر إن وُجد
        'phone',
        'tax_number',
        'price_category_id',
        'sales_rep_id',
        'customer_group_id',
        'credit_limit',
        'balance',
        'credit_status',
        'account_status',
        'approval_status',
        'created_by',
        'updated_by',
    ];

    public $translatable = ['legal_name', 'trade_name'];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'balance'      => 'decimal:2',
    ];

    /* ================= علاقات جغرافية ================= */
    public function country()
    {
        return $this->belongsTo(\App\models\country::class, 'country_id');
    }

    public function governorate()
    {
        return $this->belongsTo(\App\models\governorate::class, 'governorate_id');
    }

    // مُسمّى مختلف لتفادي التصادم مع عمود city النصّي
    public function cityRel()
    {
        return $this->belongsTo(\App\models\city::class, 'city_id');
    }

    public function area()
    {
        return $this->belongsTo(\App\models\area::class, 'area_id');
    }

    /* ================= علاقات مرجعية ================= */
    public function salesRep()
    {
        return $this->belongsTo(User::class, 'sales_rep_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /* ================= علاقات تفصيلية ================= */
    public function addresses()
    {
        return $this->hasMany(\App\models\customer\customeraddress::class, 'customer_id');
    }

    public function contacts()
    {
        return $this->hasMany(\App\models\customer\customercontact::class, 'customer_id');
    }

    public function credit()
    {
        return $this->hasOne(\App\models\customer\customercredit::class, 'customer_id');
    }

    public function pricing()
    {
        return $this->hasMany(\App\models\customer\customerpricing::class, 'customer_id');
    }

    public function documents()
    {
        return $this->hasMany(\App\models\customer\customerdocument::class, 'customer_id');
    }

    public function approvals()
    {
        return $this->hasMany(\App\models\customer\customerapproval::class, 'customer_id');
    }

    public function transactions()
    {
        return $this->hasMany(\App\models\customer\customertransaction::class, 'customer_id');
    }

   
}

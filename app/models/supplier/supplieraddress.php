<?php

namespace App\models\supplier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class supplieraddress extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'supplier_addresses';

    protected $fillable = [
        'supplier_id','label','address_line','postal_code',
        'country_id','governorate_id','city_id','area_id',
        'is_default'
    ];

    public $translatable = ['label','address_line'];

    protected $casts = [
        'label'=>'array',
        'address_line'=>'array',
        'is_default'=>'boolean',
    ];

    public function supplier()    { return $this->belongsTo(supplier::class); }
    public function country()     { return $this->belongsTo(\App\models\country::class,'country_id'); }
    public function governorate() { return $this->belongsTo(\App\models\governorate::class,'governorate_id'); }
    public function city()        { return $this->belongsTo(\App\models\city::class,'city_id'); }
    public function area()        { return $this->belongsTo(\App\models\area::class,'area_id'); }
}

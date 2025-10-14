<?php

namespace App\models\product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class product extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'products';
    protected $fillable = [
        'sku','barcode','name','description','unit_id','category_id',
        'tax_rate','opening_stock','status'
    ];
    public $translatable = ['name','description'];

    // علاقات
    public function unit()      { return $this->belongsTo(\App\models\product\unit::class); }
    public function category()  { return $this->belongsTo(\App\models\product\category::class); }
    public function priceItems(){ return $this->hasMany(\App\models\pricing\price_item::class,'product_id'); }
}

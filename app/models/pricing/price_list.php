<?php

namespace App\models\pricing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class price_list extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'price_lists';
    protected $fillable = [
        'name',
        'sales_channel_id',
        'customer_id',
        'valid_from',
        'valid_to',
        'status',
    ];
    public $translatable = ['name'];

    public function items(){ return $this->hasMany(price_item::class); }
    public function channel(){ return $this->belongsTo(\App\models\product\sales_channel::class,'sales_channel_id'); }
    public function group()  { return $this->belongsTo(\App\models\product\customer_group::class,'customer_group_id'); }
}

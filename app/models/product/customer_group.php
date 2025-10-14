<?php

namespace App\models\product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class customer_group extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'customer_groups';
    protected $fillable = ['name','code'];
    public $translatable = ['name'];
}

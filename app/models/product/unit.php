<?php

namespace App\models\product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class unit extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'units';
    protected $fillable = ['name','code'];
    public $translatable = ['name'];

    public function products() { return $this->hasMany(product::class); }
}

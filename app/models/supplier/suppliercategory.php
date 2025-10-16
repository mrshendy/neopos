<?php

namespace App\models\supplier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class suppliercategory extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'supplier_categories';

    protected $fillable = ['name','status'];

    public $translatable = ['name'];

    protected $casts = ['name'=>'array'];

    public function suppliers(){ return $this->hasMany(supplier::class,'supplier_category_id'); }
}

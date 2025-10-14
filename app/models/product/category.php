<?php

namespace App\models\product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class category extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'categories';
    protected $fillable = ['name','parent_id'];
    public $translatable = ['name'];

    public function parent(){ return $this->belongsTo(self::class,'parent_id'); }
    public function children(){ return $this->hasMany(self::class,'parent_id'); }
    public function products(){ return $this->hasMany(product::class); }
}

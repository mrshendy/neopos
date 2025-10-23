<?php

namespace App\models\product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class category extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $table = 'categories';

    protected $fillable = ['name', 'description', 'status'];

    public $translatable = ['name', 'description'];

protected $casts = ['name' => 'array'];

    public function products()
    {
        return $this->hasMany(product::class);
    }
}

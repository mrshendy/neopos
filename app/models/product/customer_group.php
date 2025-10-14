<?php

namespace App\models\product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class customer_group extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $table = 'customer_groups';

    protected $fillable = ['name', 'code'];

    public $translatable = ['name'];

    // ✅ العلاقة مع العملاء
    public function customers()
    {
        return $this->hasMany(\App\models\customer\customer::class, 'customer_group_id');
    }
}

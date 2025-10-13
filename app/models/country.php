<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
class country extends Model
{

    use HasTranslations;
    protected $fillable = [
        'name',
        'notes',
        'user_add',

    ];
    public $translatable = ['name'];
    protected $table = 'country';
    public $timestamps = true;
    use SoftDeletes;
    protected $dates = ['deleted_at'];


// app/models/customer/country.php
public function governorates()
{
    return $this->hasMany(\App\models\customer\governorate::class, 'country_id');
}

}

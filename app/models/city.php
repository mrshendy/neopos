<?php

namespace App\models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
class City extends Model
{
    use HasTranslations;
    protected $fillable = [
        'name',
        'id_country',
        'id_governorate',
        'user_add',

    ];
    public $translatable = ['name'];
    protected $table = 'city';
    public $timestamps = true;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

  public function governorate()
{
    return $this->belongsTo(\App\models\customer\governorate::class, 'governorate_id');
}
public function areas()
{
    return $this->hasMany(\App\models\customer\area::class, 'city_id');
}

}

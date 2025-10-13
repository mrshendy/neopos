<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class area extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'id_country',
        'id_governorate',
        'id_city',
        'notes',
        'user_add',

    ];

    public $translatable = ['name'];

    protected $table = 'area';

    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function governoratees()
    {
        return $this->belongsTo(governorate::class, 'id_governorate');
    }

    public function country()
    {
        return $this->belongsTo(country::class, 'id_country');
    }

    public function city()
    {
        return $this->belongsTo(city::class, 'id_city');
    }

    public function customers()
    {
        return $this->hasMany(\App\models\customer\customer::class, 'area_id');
    }
}

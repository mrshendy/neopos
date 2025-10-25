<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class area extends Model
{
    use HasTranslations, SoftDeletes;

    protected $table = 'area';
    protected $primaryKey = 'id';
    public $timestamps = true;

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'id_country',
        'id_governoratees',
        'id_city',
        'status',
        'user_add',
        'user_update',
    ];

    protected $casts = [
        'name'       => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public function country()
    {
        return $this->belongsTo(country::class, 'id_country');
    }

    public function governoratees()
    {
        return $this->belongsTo(governorate::class, 'id_governoratees');
    }

    public function city()
    {
        return $this->belongsTo(city::class, 'id_city');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check() && empty($model->user_add)) {
                $model->user_add = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->user_update = auth()->id();
            }
        });
    }
}

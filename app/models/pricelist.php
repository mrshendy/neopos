<?php

namespace App\models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class pricelist extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    // 👇 مهم جدًا تطابق اسم الجدول بالضبط مع الموجود في قاعدة البيانات
    protected $table = 'price_lists';

    protected $fillable = [
        'name',
        'status',
    ];

    public $translatable = ['name'];
}

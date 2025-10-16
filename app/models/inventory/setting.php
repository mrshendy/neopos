<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Model;

class setting extends Model
{
    // استخدم جدول inventory_settings (عدّل لو بتستخدم settings)
    protected $table = 'inventory_settings';

    protected $fillable = ['key', 'value'];

    // نخزن value كـ JSON
    protected $casts = [
        'value' => 'array',
    ];
}

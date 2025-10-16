<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Model;

class setting extends Model
{
    protected $table = 'inventory_settings';
    protected $guarded = [];
    protected $casts = ['payload'=>'array'];
}

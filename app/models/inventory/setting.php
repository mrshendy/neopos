<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class setting extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'settings';
    protected $fillable = ['key','value'];
    protected $casts = ['value'=>'array'];
}

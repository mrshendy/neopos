<?php

namespace App\models\general;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class branch extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'branches';

    protected $fillable = [
        'name', 'address', 'status',
    ];

    public $translatable = ['name'];

    protected $casts = [
        'name' => 'array',
    ];

    public function warehouses()
    {
        return $this->hasMany(\App\models\inventory\warehouse::class, 'branch_id');
    }

    // Scopes بسيطة
    public function scopeActive($q){ return $q->where('status', 'active'); }
}

<?php

namespace App\models\unit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class unit extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'unit';
    protected $fillable = ['name','description','level','status'];

    public $translatable = ['name','description'];

    protected $casts = [
        'name'        => 'array',
        'description' => 'array',
    ];

    // Scopes للفلاتر
    public function scopeSearch($q, $term = null)
    {
        if (!$term) return $q;
        return $q->where(function($qq) use ($term) {
            $qq->where('name->ar', 'like', "%{$term}%")
               ->orWhere('name->en', 'like', "%{$term}%");
        });
    }

    public function scopeLevel($q, $level = null)
    {
        if ($level) $q->where('level', $level);
        return $q;
    }

    public function scopeStatus($q, $status = null)
    {
        if ($status) $q->where('status', $status);
        return $q;
    }
}

<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class warehouse extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'warehouses';

    protected $fillable = [
        'name',
        'code',
        'branch_id',
        'status',
        'warehouse_type',
        'manager_ids',
        'address',
        'category_id',
        'product_ids',
    ];

    protected array $translatable = ['name'];

    protected $casts = [
        'name'        => 'array',
        'manager_ids' => 'array',
        'product_ids' => 'array',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\models\general\branch::class, 'branch_id');
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }

    public function scopeForBranch($q, ?int $branchId)
    {
        return $branchId ? $q->where('branch_id', $branchId) : $q;
    }

    public function setCodeAttribute($value): void
    {
        $this->attributes['code'] = strtoupper(trim((string) $value));
    }
}

<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class item extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'items';

    protected $fillable = [
        'name','sku','uom','track_batch','track_serial','status'
    ];

    public $translatable = ['name'];

    protected $casts = [
        'name' => 'array',
        'track_batch' => 'boolean',
        'track_serial' => 'boolean',
    ];

    public function batches() { return $this->hasMany(batch::class, 'item_id'); }
    public function serials() { return $this->hasMany(serial::class, 'item_id'); }
    public function reorderLevels() { return $this->hasMany(reorder_level::class, 'item_id'); }
}

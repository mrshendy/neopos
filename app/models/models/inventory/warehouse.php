<?php

namespace App\models\inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class warehouse extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'warehouses';

    protected $fillable = ['name','code','branch_id','status'];
    public $translatable = ['name'];
    protected $casts = ['name'=>'array'];

    public function batches(){ return $this->hasMany(batch::class, 'warehouse_id'); }
    public function serials(){ return $this->hasMany(serial::class, 'warehouse_id'); }
}

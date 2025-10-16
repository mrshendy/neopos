<?php

namespace App\models\supplier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class suppliercontact extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'supplier_contacts';

    protected $fillable = [
        'supplier_id','name','role','phone','email','is_primary'
    ];

    public $translatable = ['name','role'];

    protected $casts = [
        'name'=>'array',
        'role'=>'array',
        'is_primary'=>'boolean',
    ];

    public function supplier(){ return $this->belongsTo(supplier::class); }
}

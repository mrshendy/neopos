<?php

namespace App\Models\supplier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class qualitydoctype extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'quality_doc_types';

    protected $fillable = ['name','validity_days','required','status'];

    public $translatable = ['name'];

    protected $casts = [
        'name'=>'array',
        'required'=>'boolean',
        'validity_days'=>'integer',
    ];

    public function supplierDocs(){ return $this->hasMany(supplierqualitydoc::class,'quality_doc_type_id'); }
}

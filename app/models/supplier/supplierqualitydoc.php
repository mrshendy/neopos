<?php

namespace App\Models\supplier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class supplierqualitydoc extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'supplier_quality_docs';

    protected $fillable = [
        'supplier_id','quality_doc_type_id',
        'number','issue_date','expiry_date','file_path','status'
    ];

    protected $casts = [
        'issue_date'=>'date',
        'expiry_date'=>'date',
    ];

    public function supplier() { return $this->belongsTo(supplier::class); }
    public function type()     { return $this->belongsTo(qualitydoctype::class,'quality_doc_type_id'); }
}

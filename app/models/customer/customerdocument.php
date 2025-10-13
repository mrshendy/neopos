<?php

namespace App\models\customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class customerdocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_documents';

    protected $fillable = [
        'customer_id','document_type','file_path','uploaded_by','uploaded_at'
    ];

    public function customer() { return $this->belongsTo(customer::class, 'customer_id'); }
}

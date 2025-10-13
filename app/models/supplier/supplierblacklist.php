<?php

namespace App\Models\supplier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class supplierblacklist extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'supplier_blacklists';

    protected $fillable = [
        'supplier_id','reason','start_date','end_date','created_by'
    ];

    protected $casts = [
        'start_date'=>'date',
        'end_date'=>'date',
    ];

    public function supplier(){ return $this->belongsTo(supplier::class); }
}

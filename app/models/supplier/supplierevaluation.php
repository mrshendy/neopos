<?php

namespace App\models\supplier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class supplierevaluation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'supplier_evaluations';

    protected $fillable = ['supplier_id','period_start','period_end','total_score'];

    protected $casts = [
        'period_start'=>'date',
        'period_end'=>'date',
        'total_score'=>'decimal:2',
    ];

    public function supplier(){ return $this->belongsTo(supplier::class); }
    public function scores()  { return $this->hasMany(supplierevaluationscore::class,'supplier_evaluation_id'); }
}

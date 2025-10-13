<?php

namespace App\Models\supplier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class supplierevaluationscore extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'supplier_evaluation_scores';

    protected $fillable = ['supplier_evaluation_id','evaluation_criterion_id','score'];

    protected $casts = ['score'=>'decimal:2'];

    public function evaluation(){ return $this->belongsTo(supplierevaluation::class,'supplier_evaluation_id'); }
    public function criterion() { return $this->belongsTo(evaluationcriterion::class,'evaluation_criterion_id'); }
}

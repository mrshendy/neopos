<?php

namespace App\models\supplier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class evaluationcriterion extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = 'evaluation_criteria';

    protected $fillable = ['name','weight'];

    public $translatable = ['name'];

    protected $casts = ['name'=>'array','weight'=>'integer'];

    public function scores(){ return $this->hasMany(supplierevaluationscore::class,'evaluation_criterion_id'); }
}
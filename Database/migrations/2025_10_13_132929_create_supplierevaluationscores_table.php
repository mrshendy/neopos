<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('supplier_evaluation_scores', function (Blueprint $t) {
            $t->id();
            $t->foreignId('supplier_evaluation_id')->constrained('supplier_evaluations')->cascadeOnDelete();
            $t->foreignId('evaluation_criterion_id')->constrained('evaluation_criteria');
            $t->decimal('score', 5, 2)->default(0);
            $t->timestamps();
            $t->softDeletes();

            $t->unique(['supplier_evaluation_id', 'evaluation_criterion_id'], 'uniq_eval_crit');
        });
    }

    public function down()
    {
        Schema::dropIfExists('supplier_evaluation_scores');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('supplier_evaluations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $t->date('period_start');
            $t->date('period_end');
            $t->decimal('total_score', 5, 2)->default(0);
            $t->timestamps();
            $t->softDeletes();

            $t->index(['supplier_id', 'period_start', 'period_end']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('supplier_evaluations');
    }
};

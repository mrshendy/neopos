<?php
// database/migrations/2025_10_13_000012_create_evaluation_criteria_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('evaluation_criteria', function (Blueprint $t) {
            $t->id();
            $t->json('name'); // ar/en
            $t->unsignedTinyInteger('weight'); // 0..100
            $t->timestamps();
            $t->softDeletes();
            $t->index(['weight']);
        });
    }
    public function down(): void { Schema::dropIfExists('evaluation_criteria'); }
};

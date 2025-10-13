<?php
// database/migrations/2025_10_13_000003_create_payment_terms_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payment_terms', function (Blueprint $t) {
            $t->id();
            $t->json('name'); // ar/en
            $t->unsignedSmallInteger('due_days')->default(0);
            $t->boolean('allow_partial')->default(false);
            $t->enum('status',['active','inactive'])->default('active');
            $t->timestamps();
            $t->softDeletes();
            $t->index(['status','due_days']);
        });
    }
    public function down(): void { Schema::dropIfExists('payment_terms'); }
};

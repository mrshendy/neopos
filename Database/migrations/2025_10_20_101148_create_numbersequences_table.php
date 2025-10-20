<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('number_sequences', function (Blueprint $table) {
            $table->id();
            $table->string('module')->unique();     // 'offers','coupons'
            $table->string('prefix')->default('');  // مثل: 'PROMO-2025-'
            $table->unsignedInteger('padding')->default(4);
            $table->unsignedInteger('current_no')->default(0);
            $table->boolean('active')->default(true);
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('number_sequences');
    }
};

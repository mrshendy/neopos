<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // الاسم متعدد اللغات
            $table->json('name');

            // الوصف متعدد اللغات
            $table->json('description')->nullable();

            // حالة القسم
            $table->enum('status', ['active', 'inactive'])->default('active');

            // التوقيتات والحذف الناعم
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('country', function (Blueprint $table) {
            $table->id();
            // JSON للترجمات (ar/en). نخليها NOT NULL — الكود هيملأها
            $table->json('name');

            // اختياري: كود/حالة
            $table->string('code', 10)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            // تتبّع المستخدمين
            $table->unsignedBigInteger('user_add')->default(0);     // لتفادي خطأ NOT NULL
            $table->unsignedBigInteger('user_update')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // فهارس مفيدة
            $table->index('status');
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country');
    }
};

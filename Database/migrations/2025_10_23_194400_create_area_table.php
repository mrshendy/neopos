<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('area', function (Blueprint $table) {
            $table->id();                 // BIGINT UNSIGNED
            $table->json('name');

            // ✅ جميع الـ FK كـ BIGINT UNSIGNED (مطابقة لـ $table->id() في الجداول الأب)
            $table->foreignId('id_country')
                  ->constrained('country')
                  ->restrictOnDelete()
                  ->cascadeOnUpdate();

            $table->foreignId('id_governoratees')
                  ->constrained('governorate')
                  ->restrictOnDelete()
                  ->cascadeOnUpdate();

            $table->foreignId('id_city')
                  ->constrained('city')
                  ->restrictOnDelete()
                  ->cascadeOnUpdate();

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('user_add')->default(0);
            $table->unsignedBigInteger('user_update')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['id_country','id_governoratees','id_city','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('area');
    }
};

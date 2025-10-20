<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // مثال: PROMO-2025-001
            $table->json('name');             // ترجمات
            $table->json('description')->nullable();

            // أنواع العرض
            $table->enum('type', ['percentage','fixed','bxgy','bundle'])->index();

            // قيم العرض حسب النوع
            $table->decimal('discount_value', 12, 2)->nullable(); // للنسبة/القيمة الثابتة
            $table->unsignedInteger('x_qty')->nullable();         // BxGy
            $table->unsignedInteger('y_qty')->nullable();         // BxGy
            $table->decimal('bundle_price', 12, 2)->nullable();   // Bundle

            $table->decimal('max_discount_per_order', 12, 2)->nullable();
            $table->boolean('is_stackable')->default(true);
            $table->unsignedInteger('priority')->default(100)->index();

            // سياسة التعارض
            $table->enum('policy', ['highest_priority','largest_discount'])
                  ->default('highest_priority');

            $table->enum('status', ['draft','active','paused','expired'])
                  ->default('active')->index();

            // النطاق الزمني/القنوات
            $table->dateTimeTz('start_at')->nullable()->index();
            $table->dateTimeTz('end_at')->nullable()->index();
            $table->json('days_of_week')->nullable(); // [1..7]
            $table->time('hours_from')->nullable();
            $table->time('hours_to')->nullable();
            $table->string('sales_channel')->nullable(); // pos/web/so (اختياري)

            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};

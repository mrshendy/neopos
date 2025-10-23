<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // لو العمود غير موجود، أضِفه كـ nullable
        if (!Schema::hasColumn('products', 'unit_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('unit_id')->nullable()->after('description');
                // (اختياري) مفتاح أجنبي:
                // $table->foreign('unit_id')->references('id')->on('units')->nullOnDelete();
            });
            return;
        }

        // اسقط المفتاح الأجنبي إن وُجد
        try { DB::statement('ALTER TABLE `products` DROP FOREIGN KEY `products_unit_id_foreign`'); } catch (\Throwable $e) {}
        // عدل العمود ليصبح NULL
        DB::statement('ALTER TABLE `products` MODIFY `unit_id` BIGINT UNSIGNED NULL');

        // (اختياري) أعد المفتاح الأجنبي
        // DB::statement('ALTER TABLE `products` ADD CONSTRAINT `products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units`(`id`) ON DELETE SET NULL');
    }

    public function down(): void
    {
        // ارجاعه لعدم السماح بـ NULL (إن أردت)
        // ملاحظة: لو لديك بيانات NULL ستفشل العملية، لذا عدّلها قبل الرجوع.
        try { DB::statement('ALTER TABLE `products` DROP FOREIGN KEY `products_unit_id_foreign`'); } catch (\Throwable $e) {}
        DB::statement('ALTER TABLE `products` MODIFY `unit_id` BIGINT UNSIGNED NOT NULL');
        // (اختياري) أعد المفتاح الأجنبي القديم حسب احتياجك.
    }
};

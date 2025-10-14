<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 0) لو فيه FK قديم على customer_group_id نزله بالاسم الصحيح ديناميكياً
        $fk = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'price_lists'
              AND COLUMN_NAME = 'customer_group_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ");
        if ($fk && isset($fk->CONSTRAINT_NAME)) {
            try { DB::statement("ALTER TABLE `price_lists` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`"); } catch (\Throwable $e) {}
        }

        // 1) إعادة تسمية العمود بصيغة MariaDB (CHANGE COLUMN) مع تحديد النوع
        if (Schema::hasColumn('price_lists', 'customer_group_id')) {
            // BIGINT(20) UNSIGNED NULL ليطابق نوع العمود الأصلي
            DB::statement("ALTER TABLE `price_lists` CHANGE COLUMN `customer_group_id` `customer_id` BIGINT(20) UNSIGNED NULL");
        }

        // 2) تأكيد الـ NULL + إضافة FK جديد إلى customers ON DELETE SET NULL
        if (Schema::hasColumn('price_lists', 'customer_id')) {
            // أضف فهرس لو مش موجود (بعض إصدارات MariaDB تتطلب وجود index قبل FK)
            try { DB::statement("CREATE INDEX `price_lists_customer_id_index` ON `price_lists` (`customer_id`)"); } catch (\Throwable $e) {}

            try {
                DB::statement("
                    ALTER TABLE `price_lists`
                    ADD CONSTRAINT `price_lists_customer_id_foreign`
                    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`)
                    ON DELETE SET NULL ON UPDATE CASCADE
                ");
            } catch (\Throwable $e) {}
        }

        // 3) (اختياري) تحويل name إلى JSON (سيتخطى لو غير مدعوم أو البيانات ليست JSON)
        try {
            DB::statement("ALTER TABLE `price_lists` MODIFY `name` JSON NOT NULL");
        } catch (\Throwable $e) {
            // MariaDB القديمة بتتعامل مع JSON كـ LONGTEXT — ما فيش مشكلة نخليه زي ما هو.
        }
    }

    public function down(): void
    {
        // إسقاط FK على customer_id
        $fk = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'price_lists'
              AND COLUMN_NAME = 'customer_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ");
        if ($fk && isset($fk->CONSTRAINT_NAME)) {
            try { DB::statement("ALTER TABLE `price_lists` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`"); } catch (\Throwable $e) {}
        }

        // إعادة الاسم إلى customer_group_id
        if (Schema::hasColumn('price_lists', 'customer_id')) {
            DB::statement("ALTER TABLE `price_lists` CHANGE COLUMN `customer_id` `customer_group_id` BIGINT(20) UNSIGNED NULL");
        }

        // (اختياري) إعادة name إلى LONGTEXT
        try {
            DB::statement("ALTER TABLE `price_lists` MODIFY `name` LONGTEXT NOT NULL");
        } catch (\Throwable $e) {}
    }
};

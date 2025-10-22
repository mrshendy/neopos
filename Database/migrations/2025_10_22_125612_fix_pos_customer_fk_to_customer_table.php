<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // إسقاط FK القديم إن وُجد
        Schema::table('pos', function (Blueprint $table) {
            try { $table->dropForeign('pos_customer_id_foreign'); } catch (\Throwable $e) {}
            try { $table->dropForeign(['customer_id']); } catch (\Throwable $e) {}
        });

        // تعديل العمود ليكون NULL (MySQL/MariaDB)
        DB::statement('ALTER TABLE `pos` MODIFY `customer_id` BIGINT UNSIGNED NULL');

        // إنشاء FK الصحيح إلى جدول customer (مفرد)
        Schema::table('pos', function (Blueprint $table) {
            $table->foreign('customer_id')
                ->references('id')->on('customer')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('pos', function (Blueprint $table) {
            try { $table->dropForeign('pos_customer_id_foreign'); } catch (\Throwable $e) {}
            try { $table->dropForeign(['customer_id']); } catch (\Throwable $e) {}
        });

        // لو عايز ترجّع NOT NULL:
        // DB::statement('ALTER TABLE `pos` MODIFY `customer_id` BIGINT UNSIGNED NOT NULL');

        // لو كان القيد القديم على customers:
        // Schema::table('pos', function (Blueprint $table) {
        //     $table->foreign('customer_id')
        //           ->references('id')->on('customers')
        //           ->nullOnDelete()->cascadeOnUpdate();
        // });
    }
};

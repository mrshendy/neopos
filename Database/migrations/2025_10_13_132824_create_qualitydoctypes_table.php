<?php
// database/migrations/2025_10_13_000006_create_quality_doc_types_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ تأكد من عدم وجود الجدول قبل الإنشاء لتجنّب "Table already exists"
        if (!Schema::hasTable('quality_doc_types')) {
            Schema::create('quality_doc_types', function (Blueprint $t) {
                $t->id();
                $t->json('name'); // {"ar":"...", "en":"..."}
                $t->unsignedSmallInteger('validity_days')->default(0)
                    ->comment('عدد أيام صلاحية الوثيقة');
                $t->boolean('required')->default(false)
                    ->comment('هل الوثيقة مطلوبة؟');
                $t->enum('status', ['active', 'inactive'])
                    ->default('active')
                    ->comment('حالة نوع الوثيقة');
                $t->timestamps();
                $t->softDeletes();

                // ✅ فهرس مركّب باسم قصير لتجنّب خطأ طول الاسم
                $t->index(['required', 'status'], 'qdoctype_req_stat_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('quality_doc_types');
    }
};

<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('payment_terms')) {
            Schema::create('payment_terms', function (Blueprint $table) {
                $table->id();
                $table->json('name');
                $table->unsignedSmallInteger('due_days')->default(0);
                $table->boolean('allow_partial')->default(false);
                $table->enum('status', ['active','inactive'])->default('active');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        // احذر: لا تقم بالحذف الأعمى إن كان الجدول قديمًا وله بيانات مهمة
        if (Schema::hasTable('payment_terms')) {
            Schema::drop('payment_terms');
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // لو الجدول موجود بالفعل، تخطّى الإنشاء
        if (Schema::hasTable('governorate')) {
            return;
        }

        Schema::create('governorate', function (Blueprint $table) {
            $table->id();

            // نفس نمط الجداول القديمة (زي city)
            $table->unsignedBigInteger('id_country')->nullable(false);
            $table->text('name');                  // نفس city (TEXT)
            $table->text('notes')->nullable();
            $table->string('user_add', 30);
            $table->string('account_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // فهارس
            $table->index(['id_country']);

            // FK لجدول Countries (اسم الجدول بالحروف كما عندك)
            $table->foreign('id_country')
                  ->references('id')->on('Countries')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        // إسقاط FK أولاً تحسبًا لبعض قواعد MySQL
        if (Schema::hasTable('governorate')) {
            Schema::table('governorate', function (Blueprint $table) {
                // احذر: اسم القيد قد يختلف حسب إصدار MySQL/Laravel
                // لو واجهت خطأ هنا، يمكن تجاهله أو استخدام معلومات schema:show
                try { $table->dropForeign(['id_country']); } catch (\Throwable $e) {}
            });
        }
        Schema::dropIfExists('governorate');
    }
};

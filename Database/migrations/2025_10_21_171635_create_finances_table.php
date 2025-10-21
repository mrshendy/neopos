<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('finance', function (Blueprint $table) {
            $table->id();
            // اسم الخزنة متعدد اللغات
            $table->json('name');
            // ربط بفرع (اختياري) — عدّل اسم جدول الفروع لديك إذا اختلف
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            // العملة (اختياري: اتركه نصيًا أو اربطه بجدول العملات لديك)
            $table->unsignedBigInteger('currency_id')->nullable()->index();

            // ترقيم الإيصالات المبدئي للخزنة
            $table->string('receipt_prefix', 10)->default('CBX');
            $table->unsignedBigInteger('next_number')->default(1);

            // سياسات
            $table->boolean('allow_negative')->default(false);
            $table->enum('status', ['active','inactive'])->default('active');

            // ملاحظات (متعددة اللغات)
            $table->json('notes')->nullable();

            // علاقات محاسبية (اختياري)
            $table->unsignedBigInteger('gl_account_id')->nullable()->index();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // مفاتيح خارجية (اجعلها متوافقة مع جداولك)
            // $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            // $table->foreign('currency_id')->references('id')->on('currencies')->nullOnDelete();
            // $table->foreign('gl_account_id')->references('id')->on('accounts')->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('finance');
    }
};

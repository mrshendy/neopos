<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('finance_receipts', function (Blueprint $table) {
            $table->id();

            // الخزينة
            $table->foreignId('finance_settings_id')
                  ->constrained('finance_settings')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            // التاريخ والترقيم
            $table->dateTime('receipt_date')->index();
            $table->string('doc_no', 50)->nullable()->unique(); // مثل RCP-00001
            $table->string('reference', 100)->nullable();

            // طريقة الدفع
            $table->enum('method', ['cash','bank','pos','transfer'])->default('cash')->index();

            // مبالغ
            $table->decimal('amount_total', 18, 2)->default(0);
            $table->decimal('return_amount', 18, 2)->default(0);

            // الحالة (نشط/ملغي)
            $table->enum('status', ['active','canceled'])->default('active')->index();
            $table->json('cancel_reason')->nullable(); // مترجمة
            $table->unsignedBigInteger('canceled_by')->nullable()->index();
            $table->dateTime('canceled_at')->nullable();

            // ملاحظات مترجمة
            $table->json('notes')->nullable();

            // روابط اختيارية
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();

            // تتبع
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_receipts');
    }
};

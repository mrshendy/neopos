<?php
// database/migrations/2025_10_22_000001_create_finance_movements_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('finance_movements', function (Blueprint $table) {
            $table->id();

            // ربط بالخزينة (finance_settings)
            $table->foreignId('finance_settings_id')
                  ->constrained('finance_settings')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            // بيانات أساسية
            $table->date('movement_date')->index();
            $table->enum('direction', ['in','out'])->index(); // قبض/صرف
            $table->decimal('amount', 18, 2);
            $table->unsignedBigInteger('currency_id')->nullable(); // لو عندك جدول عملات اربطه لاحقًا

            // طريقة الدفع + رقم مستند/مرجع
            $table->enum('method', ['cash','bank','pos','transfer'])->default('cash')->index();
            $table->string('doc_no', 50)->nullable()->index();      // رقم الحركة
            $table->string('reference', 100)->nullable();           // مرجع خارجي

            // الحالة
            $table->enum('status', ['draft','posted','void'])->default('draft')->index();

            // ملاحظات مترجمة
            $table->json('notes')->nullable();

            // تتبع
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_movements');
    }
};

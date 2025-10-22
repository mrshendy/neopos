<?php
// database/migrations/2025_10_22_120000_create_finance_handovers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('finance_handovers', function (Blueprint $table) {
            $table->id();

            // الخزينة المعنية
            $table->foreignId('finance_settings_id')
                  ->constrained('finance_settings')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            // من يسلم / لمن يتم التسليم
            $table->unsignedBigInteger('delivered_by')->nullable()->index();
            $table->unsignedBigInteger('received_by')->nullable()->index();

            // التواريخ
            $table->dateTime('handover_date')->index();
            $table->dateTime('received_at')->nullable();

            // أرقام/ترقيم
            $table->string('doc_no', 50)->nullable()->unique(); // مثل HND-00001

            // مبالغ
            $table->decimal('amount_expected', 18, 2)->default(0);
            $table->decimal('amount_counted', 18, 2)->default(0);
            $table->decimal('difference', 18, 2)->default(0); // counted - expected

            // حالة التسليم
            $table->enum('status', ['draft','submitted','received','rejected'])->default('draft')->index();

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
        Schema::dropIfExists('finance_handovers');
    }
};

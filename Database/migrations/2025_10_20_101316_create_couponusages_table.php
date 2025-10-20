<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('coupon_id')->constrained('coupons')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();

            // رقم الطلب/الفاتورة: اتركه بلا FK لو جدول الطلبات غير ثابت لديك
            $table->unsignedBigInteger('order_id')->nullable();

            $table->decimal('amount_discounted', 12, 2)->default(0);
            $table->dateTimeTz('used_at')->index();

            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
    }
};

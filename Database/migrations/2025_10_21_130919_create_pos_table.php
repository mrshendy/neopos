<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // لو الجدول موجود بالفعل ما نكرّره
        if (Schema::hasTable('pos')) {
            return;
        }

        // اكتشاف أسماء الجداول المرجعية (singular/plural)
        $warehouseTable = Schema::hasTable('warehouse') ? 'warehouse' : (Schema::hasTable('warehouses') ? 'warehouses' : null);
        $customerTable  = Schema::hasTable('customer')  ? 'customer'  : (Schema::hasTable('customers')  ? 'customers'  : null);
        $userTable      = Schema::hasTable('users')     ? 'users'     : null;

        Schema::create('pos', function (Blueprint $table) use ($warehouseTable, $customerTable, $userTable) {
            $table->id();

            $table->string('pos_no', 40)->unique();
            $table->date('pos_date')->index();

            $table->enum('status', ['draft', 'approved', 'posted', 'cancelled'])->default('draft')->index();

            // علاقات أساسية
            $table->unsignedBigInteger('warehouse_id')->nullable()->index();
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();

            // الأرقام المجمّعة
            $table->decimal('subtotal', 24, 4)->default(0);
            $table->decimal('discount', 24, 4)->default(0);
            $table->decimal('tax', 24, 4)->default(0);
            $table->decimal('grand_total', 24, 4)->default(0);

            // ملاحظات متعددة اللغات لاحقًا
            $table->json('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // مفاتيح أجنبية (لو الجداول موجودة)
            if ($warehouseTable) {
                $table->foreign('warehouse_id')
                    ->references('id')->on($warehouseTable)
                    ->restrictOnDelete()
                    ->cascadeOnUpdate();
            }

            if ($customerTable) {
                $table->foreign('customer_id')
                    ->references('id')->on($customerTable)
                    ->nullOnDelete()
                    ->cascadeOnUpdate();
            }

            if ($userTable) {
                $table->foreign('user_id')
                    ->references('id')->on($userTable)
                    ->nullOnDelete()
                    ->cascadeOnUpdate();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos');
    }
};

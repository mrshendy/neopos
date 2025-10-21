<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pos_lines')) {
            return;
        }

        // اكتشاف أسماء الجداول المرجعية
        $posTable       = Schema::hasTable('pos')       ? 'pos'       : null;
        $productTable   = Schema::hasTable('product')   ? 'product'   : (Schema::hasTable('products')   ? 'products'   : null);
        $unitTable      = Schema::hasTable('unit')      ? 'unit'      : (Schema::hasTable('units')      ? 'units'      : null);
        $warehouseTable = Schema::hasTable('warehouse') ? 'warehouse' : (Schema::hasTable('warehouses') ? 'warehouses' : null);

        Schema::create('pos_lines', function (Blueprint $table) use ($posTable, $productTable, $unitTable, $warehouseTable) {
            $table->id();

            $table->unsignedBigInteger('pos_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('unit_id')->nullable()->index();
            $table->unsignedBigInteger('warehouse_id')->nullable()->index();

            // Snapshots واختيارات
            $table->string('code', 60)->nullable();       // كود المنتج snapshot
            $table->string('uom_text', 40)->nullable();   // اسم الوحدة snapshot

            $table->decimal('qty', 24, 4)->default(0);
            $table->decimal('unit_price', 24, 4)->default(0);
            $table->decimal('line_total', 24, 4)->default(0)->index();

            // اختيارية
            $table->date('expiry_date')->nullable();
            $table->string('batch_no', 80)->nullable();

            $table->json('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // مفاتيح أجنبية مرِنة
            if ($posTable) {
                $table->foreign('pos_id')
                    ->references('id')->on($posTable)
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();
            }

            if ($productTable) {
                $table->foreign('product_id')
                    ->references('id')->on($productTable)
                    ->restrictOnDelete()
                    ->cascadeOnUpdate();
            }

            if ($unitTable) {
                $table->foreign('unit_id')
                    ->references('id')->on($unitTable)
                    ->nullOnDelete()
                    ->cascadeOnUpdate();
            }

            if ($warehouseTable) {
                $table->foreign('warehouse_id')
                    ->references('id')->on($warehouseTable)
                    ->nullOnDelete()
                    ->cascadeOnUpdate();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_lines');
    }
};

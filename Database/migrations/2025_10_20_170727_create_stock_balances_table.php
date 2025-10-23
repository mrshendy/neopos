<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('product_id');
            $table->string('uom', 30)->default('UNIT');

            $table->decimal('onhand', 24, 4)->default(0);

            // معلومات آخر حركة (اختياري)
            $table->unsignedBigInteger('last_trx_id')->nullable();
            $table->date('last_trx_date')->nullable();

            $table->timestamps();

            $table->unique(['warehouse_id','product_id','uom'], 'ux_bal_wh_prod_uom');
            $table->index(['product_id','warehouse_id'], 'ix_bal_prod_wh');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_balances');
    }
};

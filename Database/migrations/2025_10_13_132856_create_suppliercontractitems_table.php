<?php
// database/migrations/2025_10_13_000009_create_supplier_contract_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supplier_contract_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('supplier_contract_id')->constrained('supplier_contracts')->cascadeOnDelete();

            // ربط المنتج: لو عندك جدول products اربط FK؛ هنا نخزن مؤقتًا
            $t->string('product_sku')->nullable();
            $t->json('product_name')->nullable(); // ar/en
            $t->decimal('price',12,4);
            $t->unsignedInteger('min_qty')->nullable();
            $t->unsignedInteger('max_qty')->nullable();

            $t->timestamps();
            $t->softDeletes();

            $t->index(['supplier_contract_id','product_sku']);
        });
    }
    public function down(): void { Schema::dropIfExists('supplier_contract_items'); }
};

<?php
// database/migrations/2025_10_13_000010_create_supplier_discounts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supplier_discounts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();

            $t->enum('type',['percentage','amount','tiered']);
            $t->decimal('percentage',8,2)->nullable();
            $t->decimal('amount',12,4)->nullable();
            $t->unsignedInteger('from_qty')->nullable();
            $t->unsignedInteger('to_qty')->nullable();
            $t->enum('status',['active','inactive'])->default('active');

            $t->timestamps();
            $t->softDeletes();

            $t->index(['supplier_id','type','status']);
        });
    }
    public function down(): void { Schema::dropIfExists('supplier_discounts'); }
};

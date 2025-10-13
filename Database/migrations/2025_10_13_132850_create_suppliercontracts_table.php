<?php
// database/migrations/2025_10_13_000008_create_supplier_contracts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supplier_contracts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $t->foreignId('payment_term_id')->nullable()->constrained('payment_terms');

            $t->date('start_date');
            $t->date('end_date');
            $t->enum('status',['draft','active','inactive'])->default('active');

            $t->timestamps();
            $t->softDeletes();

            $t->index(['supplier_id','status','start_date','end_date']);
        });
    }
    public function down(): void { Schema::dropIfExists('supplier_contracts'); }
};

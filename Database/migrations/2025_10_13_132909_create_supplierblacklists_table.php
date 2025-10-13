<?php
// database/migrations/2025_10_13_000011_create_supplier_blacklists_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supplier_blacklists', function (Blueprint $t) {
            $t->id();
            $t->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $t->text('reason')->nullable();
            $t->date('start_date');
            $t->date('end_date')->nullable();
            $t->unsignedBigInteger('created_by')->nullable();

            $t->timestamps();
            $t->softDeletes();

            $t->index(['supplier_id','start_date','end_date']);
        });
    }
    public function down(): void { Schema::dropIfExists('supplier_blacklists'); }
};

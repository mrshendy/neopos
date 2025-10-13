<?php
// database/migrations/2025_10_13_000005_create_supplier_contacts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supplier_contacts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $t->json('name'); // ar/en
            $t->json('role')->nullable(); // ar/en
            $t->string('phone')->nullable();
            $t->string('email')->nullable();
            $t->boolean('is_primary')->default(false);
            $t->timestamps();
            $t->softDeletes();
            $t->index(['supplier_id','is_primary']);
        });
    }
    public function down(): void { Schema::dropIfExists('supplier_contacts'); }
};

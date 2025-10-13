<?php
// database/migrations/2025_10_13_000002_create_supplier_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supplier_categories', function (Blueprint $t) {
            $t->id();
            $t->json('name'); // ar/en
            $t->enum('status',['active','inactive'])->default('active');
            $t->timestamps();
            $t->softDeletes();
            $t->index(['status']);
        });
    }
    public function down(): void { Schema::dropIfExists('supplier_categories'); }
};

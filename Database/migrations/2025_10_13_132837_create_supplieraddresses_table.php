<?php
// database/migrations/2025_10_13_000004_create_supplier_addresses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supplier_addresses', function (Blueprint $t) {
            $t->id();
            $t->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();

            $t->json('label')->nullable();        // ar/en
            $t->json('address_line')->nullable(); // ar/en
            $t->string('postal_code')->nullable();

            $t->unsignedBigInteger('country_id')->nullable();
            $t->unsignedBigInteger('governorate_id')->nullable();
            $t->unsignedBigInteger('city_id')->nullable();
            $t->unsignedBigInteger('area_id')->nullable();

            $t->boolean('is_default')->default(false);

            $t->timestamps();
            $t->softDeletes();

            $t->foreign('country_id')->references('id')->on('Countries');
            $t->foreign('governorate_id')->references('id')->on('governorate');
            $t->foreign('city_id')->references('id')->on('city');
            $t->foreign('area_id')->references('id')->on('Area');

            $t->index(['supplier_id','is_default']);
        });
    }
    public function down(): void { Schema::dropIfExists('supplier_addresses'); }
};

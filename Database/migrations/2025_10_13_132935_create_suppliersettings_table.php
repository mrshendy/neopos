<?php
// database/migrations/2025_10_13_000014_create_supplier_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supplier_settings', function (Blueprint $t) {
            $t->id();
            $t->unsignedSmallInteger('alert_days_before_expiry')->default(30);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('supplier_settings'); }
};

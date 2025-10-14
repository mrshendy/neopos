<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('customer_groups', function (Blueprint $table) {
            $table->id();
            $table->json('name'); // ar/en
            $table->string('code', 50)->unique();
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('customer_groups'); }
};

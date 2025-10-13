<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();

            $table->enum('type', ['bill_to','ship_to','visit_to'])->default('bill_to');

            $table->json('company_name')->nullable();   // {ar,en}
            $table->json('contact_person')->nullable(); // {ar,en}

            $table->string('phone', 50)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('street', 255)->nullable();
            $table->string('postal_code', 20)->nullable();

            $table->boolean('is_default')->default(false);

            $table->softDeletes();
            $table->timestamps();

            $table->index(['customer_id','type','is_default']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};

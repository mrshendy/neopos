<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_credit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();

            $table->decimal('credit_limit', 12, 2)->default(0);
            $table->string('payment_terms', 50)->nullable(); // NET30, NET45 ...
            $table->unsignedInteger('grace_period_days')->default(0);
            $table->decimal('late_fee_percent', 5, 2)->default(0);
            $table->decimal('available_credit', 12, 2)->default(0);

            $table->enum('status', ['active','suspended','overdue'])->default('active');

            $table->softDeletes();
            $table->timestamps();

            $table->unique('customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_credit');
    }
};

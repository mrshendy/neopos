<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();

            $table->enum('type', ['invoice','receipt','return']);
            $table->string('reference_no', 50)->nullable();
            $table->date('date');

            $table->decimal('amount', 12, 2);
            $table->decimal('balance_after', 12, 2)->default(0);

            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');

            $table->softDeletes();
            $table->timestamps();

            $table->index(['customer_id','type','date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_transactions');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('finance_settings', function (Blueprint $table) {
            $table->id();

            $table->json('name');                 // AR/EN
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('warehouse_id')->nullable()->index();
            $table->unsignedBigInteger('currency_id')->nullable()->index();

            $table->boolean('is_available')->default(true);
            $table->boolean('allow_negative_stock')->default(false);

            $table->unsignedInteger('return_window_days')->default(14);
            $table->boolean('require_return_approval')->default(false);
            $table->decimal('approval_over_amount', 12, 2)->nullable();

            $table->string('receipt_prefix', 12)->default('RET');
            $table->unsignedBigInteger('next_return_number')->default(1);

            $table->json('notes')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // علاقات (افتحها حسب جداولك)
            // $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            // $table->foreign('warehouse_id')->references('id')->on('warehouses')->nullOnDelete();
            // $table->foreign('currency_id')->references('id')->on('currencies')->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::dropIfExists('finance_settings');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();

            $table->foreignId('default_price_list_id')->nullable()->constrained('price_lists');
            $table->decimal('default_discount', 5, 2)->default(0);

            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();

            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();

            $table->decimal('special_discount', 5, 2)->default(0);
            $table->unsignedInteger('min_quantity')->default(0);

            $table->enum('status', ['active','expired','draft'])->default('draft');

            $table->softDeletes();
            $table->timestamps();

$table->index(
    ['customer_id','product_id','category_id','branch_id','status'],
    'idx_custpricing_main'
);        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_pricing');
    }
};

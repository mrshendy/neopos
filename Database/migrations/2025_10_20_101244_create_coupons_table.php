<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // CP-0001
            $table->json('name');
            $table->json('description')->nullable();

            $table->enum('type', ['percentage','fixed'])->index();
            $table->decimal('discount_value', 12, 2);

            $table->unsignedInteger('max_uses_per_customer')->default(1);
            $table->unsignedInteger('max_total_uses')->nullable(); // سقف إجمالي
            $table->unsignedInteger('used_count')->default(0);

            $table->boolean('is_stackable')->default(false);

            $table->enum('status', ['active','paused','expired'])
                  ->default('active')->index();

            $table->dateTimeTz('start_at')->nullable()->index();
            $table->dateTimeTz('end_at')->nullable()->index();

            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};

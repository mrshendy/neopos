<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->id();

            $table->string('code', 40)->unique();        // كود العميل
            $table->enum('type', ['person','company'])->default('person');

            // حقول مترجمة JSON
            $table->json('name');                         // {"ar":"...", "en":"..."}
            $table->json('address')->nullable();         // {"ar":"...", "en":"..."}
            $table->json('city')->nullable();            // {"ar":"...", "en":"..."}

            // حقول عامة
            $table->string('country', 80)->nullable();
            $table->string('email', 120)->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('mobile', 40)->nullable();
            $table->string('tax_no', 60)->nullable();
            $table->string('commercial_no', 60)->nullable();

            $table->enum('status', ['active','inactive'])->default('active');

            $table->json('notes')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['type','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};

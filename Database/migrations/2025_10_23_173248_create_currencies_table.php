<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();                 // مثال: EGP, USD
            $table->json('name');                                  // {"ar":"جنيه مصري","en":"Egyptian Pound"}
            $table->string('symbol', 10)->nullable();              // مثال: E£ , $
            $table->unsignedTinyInteger('decimal_places')->default(2);
            $table->string('thousand_separator', 2)->default(',');
            $table->string('decimal_separator', 2)->default('.');
            $table->decimal('exchange_rate', 18, 6)->default(1);   // مقابل العملة الافتراضية
            $table->boolean('is_default')->default(false);
            $table->enum('status', ['active','inactive'])->default('active');
            $table->json('notes')->nullable();

            // تتبع المستخدم
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'is_default']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};

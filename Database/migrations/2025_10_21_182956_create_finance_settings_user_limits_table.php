<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('finance_settings_user_limits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('finance_settings_id')->index();
            $table->unsignedBigInteger('user_id')->index();

            $table->unsignedInteger('daily_count_limit')->nullable();       // عدد ارتجاعات/يوم
            $table->decimal('daily_amount_limit', 12, 2)->nullable();       // حد مالي/يوم
            $table->boolean('require_supervisor')->default(false);          // يحتاج موافقة

            $table->boolean('active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('finance_settings_id')->references('id')->on('finance_settings')->cascadeOnDelete();
            // $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
    public function down(): void {
        Schema::dropIfExists('finance_settings_user_limits');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    if (!Schema::hasTable('governorate')) {
        Schema::create('governorate', function (Blueprint $table) {
            $table->id();
            $table->json('name');

            // اختَر النوع المتوافق مع country.id
            $table->foreignId('id_country')
                  ->constrained('country')
                  ->restrictOnDelete()
                  ->cascadeOnUpdate();

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('user_add')->default(0);
            $table->unsignedBigInteger('user_update')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['id_country','status']);
        });
    }
}


    public function down(): void
    {
        Schema::dropIfExists('governorate');
    }
};

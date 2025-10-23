<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            // اسم الفرع (عربي/إنجليزي) كـ JSON
            $table->json('name');
            $table->string('address')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes(); // تفضيلك لدعم soft delete
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};

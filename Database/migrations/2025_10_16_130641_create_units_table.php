<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('unit', function (Blueprint $table) {
            $table->id();
            // أسماء وترجمات
            $table->json('name');          // ['ar' => '', 'en' => '']
            $table->json('description')->nullable();

            // المستوى: صغرى/وسطى/كبرى
            $table->enum('level', ['minor','middle','major'])->default('minor');

            // الحالة
            $table->enum('status', ['active','inactive'])->default('active');

            $table->softDeletes();
            $table->timestamps();

            $table->index(['level','status']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('unit');
    }
};

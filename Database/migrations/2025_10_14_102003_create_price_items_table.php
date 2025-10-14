<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('price_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('price_list_id')->constrained('price_lists')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('min_qty')->default(1);
            $table->unsignedInteger('max_qty')->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // منع تكرار نفس المنتج بنفس الفترة داخل نفس القائمة (تقريبية — هنفحص بالتطبيق)
            $table->unique(['price_list_id','product_id','valid_from','valid_to'],'uq_price_scope');
        });
    }
    public function down(): void { Schema::dropIfExists('price_items'); }
};

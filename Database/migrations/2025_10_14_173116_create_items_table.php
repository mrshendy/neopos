<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->json('name')->nullable();     // ['ar'=>'','en'=>'']
            $table->string('sku')->unique();
            $table->string('uom', 50)->default('unit');
            $table->boolean('track_batch')->default(true);
            $table->boolean('track_serial')->default(false);
            $table->enum('status', ['active','inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status']);
        });
    }
    public function down(): void { Schema::dropIfExists('products'); }
};

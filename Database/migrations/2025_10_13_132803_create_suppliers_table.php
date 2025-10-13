<?php
// database/migrations/2025_10_13_000003_create_suppliers_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->json('name');
            $table->string('commercial_register')->nullable();
            $table->string('tax_number')->nullable();

            // الأعمدة أولاً
            $table->unsignedBigInteger('supplier_category_id')->nullable();
            $table->unsignedBigInteger('payment_term_id')->nullable();

            // جغرافياك القائمة
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('governorate_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();

            $table->enum('status', ['active','inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['code','status','governorate_id','city_id']);
        });

        // أضف القيود بعد التأكد أن الجداول الموجودة جاهزة
        Schema::table('suppliers', function (Blueprint $table) {
            $table->foreign('supplier_category_id')->references('id')->on('supplier_categories');
            $table->foreign('payment_term_id')->references('id')->on('payment_terms');

            // أسماء جغرافياك كما هي (Case Sensitive)
            $table->foreign('country_id')->references('id')->on('Countries');
            $table->foreign('governorate_id')->references('id')->on('governorate');
            $table->foreign('city_id')->references('id')->on('city');
            $table->foreign('area_id')->references('id')->on('Area');
        });
    }

    public function down(): void {
        Schema::dropIfExists('suppliers');
    }
};

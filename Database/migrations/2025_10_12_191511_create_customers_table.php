<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // أكواد وأسماء (ثنائية اللغة)
            $table->string('code', 20)->unique();
            $table->json('legal_name');              // {ar,en}
            $table->json('trade_name')->nullable();  // {ar,en}

            // نوع وقناة وحالات (اعملناها قبل الفهارس)
            $table->enum('type', ['individual','company','b2b','b2c'])->default('b2b');
            $table->enum('channel', ['retail','wholesale','online','pharmacy'])->nullable();
            $table->enum('account_status', ['active','inactive','suspended'])->default('active');
            $table->enum('credit_status', ['active','on_hold','over_limit'])->default('active');
            $table->enum('approval_status', ['draft','under_review','approved','rejected'])->default('draft');

            // جغرافيا (FKs تُضاف بعد الإنشاء بسبب أسماء الجداول)
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('governorate_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();

            // حقول إضافية
            $table->string('city', 100)->nullable(); // نص حر (اختياري)
            $table->string('phone', 50)->nullable();
            $table->string('tax_number', 50)->nullable();

            // السعر والمندوب
            $table->unsignedBigInteger('price_category_id')->nullable(); // FK لاحقًا لـ price_lists
            $table->foreignId('sales_rep_id')->nullable()->constrained('users');

            // أرصدة
            $table->decimal('credit_limit', 12, 2)->default(0);
            $table->decimal('balance', 12, 2)->default(0);

            // تتبع
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

            $table->softDeletes();
            $table->timestamps();

            // ✅ فهارس بعد تعريف الأعمدة
            $table->index(['type','channel','account_status']);
            $table->index(['country_id','governorate_id','city_id','area_id']);
        });

        // قيود خارجية اليدوية (بسبب أسماء جداول غير قياسية)
        Schema::table('customers', function (Blueprint $table) {
            // price_lists
            $table->foreign('price_category_id')
                  ->references('id')->on('price_lists')
                  ->nullOnDelete();

            // country / governorate / city / area
            $table->foreign('country_id')->references('id')->on('country')->nullOnDelete();
            $table->foreign('governorate_id')->references('id')->on('governorate')->nullOnDelete();
            $table->foreign('city_id')->references('id')->on('city')->nullOnDelete();
            $table->foreign('area_id')->references('id')->on('area')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // نحاول إسقاط الـFKs إن وُجدت
            try { $table->dropForeign(['price_category_id']); } catch (\Throwable $e) {}
            try { $table->dropForeign(['country_id']); } catch (\Throwable $e) {}
            try { $table->dropForeign(['governorate_id']); } catch (\Throwable $e) {}
            try { $table->dropForeign(['city_id']); } catch (\Throwable $e) {}
            try { $table->dropForeign(['area_id']); } catch (\Throwable $e) {}
        });
        Schema::dropIfExists('customers');
    }
};

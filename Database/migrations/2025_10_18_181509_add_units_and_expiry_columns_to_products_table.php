<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // supplier_id (لو عندك جدول suppliers)
            if (!Schema::hasColumn('products', 'supplier_id')) {
                $table->unsignedBigInteger('supplier_id')->nullable()->after('category_id');
                // اعمل القيود لو جدول suppliers موجود
                if (Schema::hasTable('suppliers')) {
                    $table->foreign('supplier_id')
                          ->references('id')->on('suppliers')
                          ->nullOnDelete();
                }
            }

            // مصفوفة الوحدات
            if (!Schema::hasColumn('products', 'units_matrix')) {
                $table->json('units_matrix')->nullable()->after('unit_id');
            }

            // مفاتيح وحدات البيع/الشراء
            if (!Schema::hasColumn('products', 'sale_unit_key')) {
                $table->enum('sale_unit_key', ['minor','middle','major'])->nullable()->after('units_matrix');
            }
            if (!Schema::hasColumn('products', 'purchase_unit_key')) {
                $table->enum('purchase_unit_key', ['minor','middle','major'])->nullable()->after('sale_unit_key');
            }

            // حقول الصلاحية
            if (!Schema::hasColumn('products', 'expiry_enabled')) {
                $table->boolean('expiry_enabled')->default(0)->after('status');
            }
            if (!Schema::hasColumn('products', 'expiry_unit')) {
                $table->enum('expiry_unit', ['day','month','year'])->nullable()->after('expiry_enabled');
            }
            if (!Schema::hasColumn('products', 'expiry_value')) {
                $table->integer('expiry_value')->nullable()->after('expiry_unit');
            }
            if (!Schema::hasColumn('products', 'expiry_weekdays')) {
                $table->json('expiry_weekdays')->nullable()->after('expiry_value');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // لازم نفكّ الـ FK قبل حذف العمود
            if (Schema::hasColumn('products', 'supplier_id')) {
                try {
                    $table->dropForeign(['supplier_id']);
                } catch (\Throwable $e) { /* تجاهل لو مفيش FK */ }
                $table->dropColumn('supplier_id');
            }

            foreach ([
                'units_matrix',
                'sale_unit_key',
                'purchase_unit_key',
                'expiry_enabled',
                'expiry_unit',
                'expiry_value',
                'expiry_weekdays',
            ] as $col) {
                if (Schema::hasColumn('products', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

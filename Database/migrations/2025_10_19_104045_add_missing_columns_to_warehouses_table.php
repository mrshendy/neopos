<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            // code (لو مش موجود)
            if (!Schema::hasColumn('warehouses', 'code')) {
                $table->string('code', 50)->unique()->after('name');
            }

            // branch_id موجود غالباً – تجاهل

            // status موجود غالباً – تجاهل

            // warehouse_type
            if (!Schema::hasColumn('warehouses', 'warehouse_type')) {
                $table->enum('warehouse_type', ['main','sub'])->default('main')->after('status');
            }

            // manager_ids
            if (!Schema::hasColumn('warehouses', 'manager_ids')) {
                $table->json('manager_ids')->nullable()->after('warehouse_type');
            }

            // address
            if (!Schema::hasColumn('warehouses', 'address')) {
                $table->text('address')->nullable()->after('manager_ids');
            }

            // category_id
            if (!Schema::hasColumn('warehouses', 'category_id')) {
                // لو عندك جدول categories
                $table->foreignId('category_id')->nullable()->after('address')
                      ->constrained('categories')->nullOnDelete();
            }

            // product_ids
            if (!Schema::hasColumn('warehouses', 'product_ids')) {
                $table->json('product_ids')->nullable()->after('category_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            if (Schema::hasColumn('warehouses', 'product_ids'))   $table->dropColumn('product_ids');
            if (Schema::hasColumn('warehouses', 'category_id'))   $table->dropConstrainedForeignId('category_id');
            if (Schema::hasColumn('warehouses', 'address'))       $table->dropColumn('address');
            if (Schema::hasColumn('warehouses', 'manager_ids'))   $table->dropColumn('manager_ids');
            if (Schema::hasColumn('warehouses', 'warehouse_type'))$table->dropColumn('warehouse_type');
            if (Schema::hasColumn('warehouses', 'code'))          $table->dropColumn('code');
        });
    }
};

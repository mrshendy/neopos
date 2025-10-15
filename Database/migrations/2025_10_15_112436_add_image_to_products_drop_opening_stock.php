<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 0) لو فيه FK على item_id باسـم غير معروف: استخرجه ثم اسقطه
        $fk = DB::selectOne("
            SELECT CONSTRAINT_NAME as name
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'stock_transaction_lines'
              AND COLUMN_NAME = 'item_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ");
        if ($fk && isset($fk->name)) {
            try { DB::statement("ALTER TABLE stock_transaction_lines DROP FOREIGN KEY `{$fk->name}`"); } catch (\Throwable $e) {}
        } else {
            // محاولة باسم لارفيل الافتراضي
            try { DB::statement('ALTER TABLE stock_transaction_lines DROP FOREIGN KEY stock_transaction_lines_item_id_foreign'); } catch (\Throwable $e) {}
        }

        // 1) أضف product_id (مؤقتًا NULLable)
        Schema::table('stock_transaction_lines', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_transaction_lines', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->after('stock_transaction_id');
            }
        });

        // 2) انسخ البيانات من item_id إلى product_id
        if (Schema::hasColumn('stock_transaction_lines', 'item_id')) {
            DB::statement('UPDATE stock_transaction_lines SET product_id = item_id WHERE product_id IS NULL');
        }

        // 3) احذف item_id
        Schema::table('stock_transaction_lines', function (Blueprint $table) {
            if (Schema::hasColumn('stock_transaction_lines', 'item_id')) {
                $table->dropColumn('item_id');
            }
        });

        // 4) اجعل product_id NOT NULL بدون change()
        $nulls = DB::table('stock_transaction_lines')->whereNull('product_id')->count();
        if ($nulls === 0) {
            DB::statement('ALTER TABLE stock_transaction_lines MODIFY product_id BIGINT UNSIGNED NOT NULL');
        }

        // 5) (اختياري) أضف FK جديد على products.id
        try {
            DB::statement('ALTER TABLE stock_transaction_lines ADD CONSTRAINT stock_transaction_lines_product_id_foreign FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE CASCADE');
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        // اسقاط FK على product_id لو موجود
        $fk2 = DB::selectOne("
            SELECT CONSTRAINT_NAME as name
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'stock_transaction_lines'
              AND COLUMN_NAME = 'product_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ");
        if ($fk2 && isset($fk2->name)) {
            try { DB::statement("ALTER TABLE stock_transaction_lines DROP FOREIGN KEY `{$fk2->name}`"); } catch (\Throwable $e) {}
        } else {
            try { DB::statement('ALTER TABLE stock_transaction_lines DROP FOREIGN KEY stock_transaction_lines_product_id_foreign'); } catch (\Throwable $e) {}
        }

        // أضف item_id مؤقتًا
        Schema::table('stock_transaction_lines', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_transaction_lines', 'item_id')) {
                $table->unsignedBigInteger('item_id')->nullable()->after('stock_transaction_id');
            }
        });

        // انسخ البيانات راجعة
        if (Schema::hasColumn('stock_transaction_lines', 'product_id')) {
            DB::statement('UPDATE stock_transaction_lines SET item_id = product_id WHERE item_id IS NULL');
        }

        // احذف product_id
        Schema::table('stock_transaction_lines', function (Blueprint $table) {
            if (Schema::hasColumn('stock_transaction_lines', 'product_id')) {
                $table->dropColumn('product_id');
            }
        });

        // جعل item_id NOT NULL بدون change()
        $nulls = DB::table('stock_transaction_lines')->whereNull('item_id')->count();
        if ($nulls === 0) {
            DB::statement('ALTER TABLE stock_transaction_lines MODIFY item_id BIGINT UNSIGNED NOT NULL');
        }
    }
};

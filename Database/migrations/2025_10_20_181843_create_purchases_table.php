<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    /** هل الـ PK في الجدول BigInt؟ */
    private function pkIsBig(string $table): bool
    {
        $row = DB::table('information_schema.COLUMNS')
            ->select('DATA_TYPE')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', 'id')
            ->first();

        return $row && stripos($row->DATA_TYPE, 'bigint') !== false;
    }

    public function up(): void
    {
        // استنتاج أسماء الجداول من الموديلات الفعلية
        $whTable = (new \App\models\inventory\warehouse)->getTable(); // غالبًا: warehouse أو warehouses
        $supTable = (new \App\models\supplier\supplier)->getTable();  // supplier أو suppliers

        // تأكد إن الجداول المرجعية موجودة قبل إنشاء الـ FK
        foreach ([$whTable, $supTable] as $t) {
            if (!Schema::hasTable($t)) {
                throw new RuntimeException("جدول مرجعي غير موجود: {$t}. شغّل الميجريشن الخاصة به قبل هذا الملف.");
            }
        }

        // نوع الأعمدة المفاتيح الأجنبية (INT أو BIGINT) طبقًا للجدول المرجعي
        $whIsBig  = $this->pkIsBig($whTable);
        $supIsBig  = $this->pkIsBig($supTable);

        Schema::create('purchases', function (Blueprint $table) {
            $table->id(); // BIGINT افتراضيًا
            $table->string('purchase_no', 40)->unique();
            $table->date('purchase_date');
            $table->date('supply_date')->nullable();
            $table->enum('status', ['draft','approved','posted','cancelled'])->default('draft')->index();
            $table->json('notes')->nullable();
            $table->decimal('subtotal', 24, 4)->default(0);
            $table->decimal('discount', 24, 4)->default(0);
            $table->decimal('tax', 24, 4)->default(0);
            $table->decimal('grand_total', 24, 4)->default(0);
            $table->unsignedBigInteger('user_id')->nullable()->index(); // عدّلها لو users.id عندك INT
            $table->timestamps();
            $table->softDeletes();
            $table->index(['purchase_date','supply_date']);
        });

        // نضيف أعمدة الـ FK بعد الإنشاء (علشان نقدر نحدّد النوع ديناميكيًا)
        Schema::table('purchases', function (Blueprint $table) use ($whIsBig, $supIsBig, $whTable, $supTable) {
            // warehouse_id
            if ($whIsBig) $table->unsignedBigInteger('warehouse_id')->after('supply_date');
            else          $table->unsignedInteger('warehouse_id')->after('supply_date');

            // supplier_id
            if ($supIsBig) $table->unsignedBigInteger('supplier_id')->after('warehouse_id');
            else           $table->unsignedInteger('supplier_id')->after('warehouse_id');

            $table->index(['warehouse_id','supplier_id'], 'ix_purchases_wh_sup');

            // إضافة قيود FK بأسماء الجداول المستنتجة
            $table->foreign('warehouse_id')
                ->references('id')->on($whTable)
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('supplier_id')
                ->references('id')->on($supTable)
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('purchases')) {
            Schema::table('purchases', function (Blueprint $table) {
                // فكّ الـ FK لو موجودين
                try { $table->dropForeign(['warehouse_id']); } catch (\Throwable $e) {}
                try { $table->dropForeign(['supplier_id']); } catch (\Throwable $e) {}
            });
        }
        Schema::dropIfExists('purchase_lines');
        Schema::dropIfExists('purchases');
    }
};

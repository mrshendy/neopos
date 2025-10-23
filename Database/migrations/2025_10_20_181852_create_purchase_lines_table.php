<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

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
        // أسماء الجداول المرجعية الفعلية
        $prodTable = (new \App\models\product\product)->getTable(); // product أو products
        $unitTable = (new \App\models\unit\unit)->getTable();       // unit أو units

        foreach ([$prodTable, $unitTable, 'purchases'] as $t) {
            if (!Schema::hasTable($t)) {
                throw new RuntimeException("جدول مرجعي غير موجود: {$t}.");
            }
        }

        $prodIsBig = $this->pkIsBig($prodTable);
        $unitIsBig = $this->pkIsBig($unitTable);
        $purchIsBig = $this->pkIsBig('purchases'); // إحنا عملناه BIGINT بـ $table->id()

        Schema::create('purchase_lines', function (Blueprint $table) {
            $table->id();
            // باقي الأعمدة العادية
            $table->unsignedInteger('category_id')->nullable()->index();
            $table->string('uom', 30)->default('UNIT');
            $table->decimal('qty', 24, 4);
            $table->decimal('unit_price', 24, 4)->default(0);
            $table->decimal('line_total', 24, 4)->default(0);
            $table->date('expiry_date')->nullable();
            $table->string('batch_no', 64)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['batch_no','expiry_date']);
        });

        // إضافة أعمدة الـ FK بأنواع مطابقة تمامًا
        Schema::table('purchase_lines', function (Blueprint $table) use ($prodIsBig, $unitIsBig, $purchIsBig, $prodTable, $unitTable) {
            if ($purchIsBig) $table->unsignedBigInteger('purchase_id')->after('id');
            else             $table->unsignedInteger('purchase_id')->after('id');

            if ($prodIsBig)  $table->unsignedBigInteger('product_id')->after('purchase_id');
            else             $table->unsignedInteger('product_id')->after('purchase_id');

            if ($unitIsBig)  $table->unsignedBigInteger('unit_id')->after('product_id');
            else             $table->unsignedInteger('unit_id')->after('product_id');

            $table->index(['product_id','unit_id']);

            $table->foreign('purchase_id')
                ->references('id')->on('purchases')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')->on($prodTable)
                ->onUpdate('cascade')->onDelete('restrict');

            $table->foreign('unit_id')
                ->references('id')->on($unitTable)
                ->onUpdate('cascade')->onDelete('restrict');

            // لو عندك جدول category مفرد:
            // $table->foreign('category_id')->references('id')->on('category')->onUpdate('cascade')->onDelete('set null');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('purchase_lines')) {
            Schema::table('purchase_lines', function (Blueprint $table) {
                try { $table->dropForeign(['purchase_id']); } catch (\Throwable $e) {}
                try { $table->dropForeign(['product_id']); }  catch (\Throwable $e) {}
                try { $table->dropForeign(['unit_id']); }     catch (\Throwable $e) {}
                try { $table->dropForeign(['category_id']); } catch (\Throwable $e) {}
            });
        }
        Schema::dropIfExists('purchase_lines');
    }
};

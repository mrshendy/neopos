<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        // 1) تأكيد وجود العمود trx_no (مؤقتًا nullable لملء البيانات القديمة)
        if (!Schema::hasColumn('stock_transactions', 'trx_no')) {
            Schema::table('stock_transactions', function (Blueprint $table) {
                $table->string('trx_no', 32)->nullable()->after('id');
            });
        }

        // 2) تعبئة trx_no للسجلات القديمة الفارغة
        $rows = DB::table('stock_transactions')
            ->select('id', 'trx_date', 'trx_no')
            ->whereNull('trx_no')
            ->orWhere('trx_no', '')
            ->orderBy('id')
            ->get();

        $counters = []; // مفاتيحها: IN-YYYYMMDD => آخر تسلسل
        foreach ($rows as $row) {
            $date = $row->trx_date ? Carbon::parse($row->trx_date)->format('Ymd') : Carbon::now()->format('Ymd');
            $key  = 'IN-'.$date;
            $seq  = ($counters[$key] ?? 0) + 1;
            $counters[$key] = $seq;
            $trxNo = $key.'-'.str_pad((string)$seq, 4, '0', STR_PAD_LEFT);

            DB::table('stock_transactions')->where('id', $row->id)->update(['trx_no' => $trxNo]);
        }

        // 3) جعل العمود NOT NULL (بدون Doctrine DBAL) + إنشاء UNIQUE INDEX إذا غير موجود
        DB::statement("ALTER TABLE stock_transactions MODIFY trx_no VARCHAR(32) NOT NULL");

        $uniqueExists = DB::table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'stock_transactions')
            ->where('INDEX_NAME', 'stock_transactions_trx_no_unique')
            ->exists();

        if (!$uniqueExists) {
            DB::statement("CREATE UNIQUE INDEX stock_transactions_trx_no_unique ON stock_transactions (trx_no)");
        }

        // 4) فهارس مساعدة اختيارية
        if (Schema::hasColumn('stock_transactions', 'trx_date')) {
            $hasIdx = DB::table('information_schema.STATISTICS')->where([
                ['TABLE_SCHEMA', DB::getDatabaseName()],
                ['TABLE_NAME',   'stock_transactions'],
                ['COLUMN_NAME',  'trx_date'],
            ])->exists();
            if (!$hasIdx) {
                Schema::table('stock_transactions', function (Blueprint $table) {
                    $table->index('trx_date', 'stock_transactions_trx_date_index');
                });
            }
        }

        if (Schema::hasColumn('stock_transactions', 'warehouse_to_id')) {
            $hasIdx = DB::table('information_schema.STATISTICS')->where([
                ['TABLE_SCHEMA', DB::getDatabaseName()],
                ['TABLE_NAME',   'stock_transactions'],
                ['COLUMN_NAME',  'warehouse_to_id'],
            ])->exists();
            if (!$hasIdx) {
                Schema::table('stock_transactions', function (Blueprint $table) {
                    $table->index('warehouse_to_id', 'stock_transactions_warehouse_to_id_index');
                });
            }
        }
    }

    public function down(): void
    {
        // إزالة UNIQUE INDEX إن وُجد
        $uniqueExists = DB::table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'stock_transactions')
            ->where('INDEX_NAME', 'stock_transactions_trx_no_unique')
            ->exists();

        if ($uniqueExists) {
            DB::statement("DROP INDEX stock_transactions_trx_no_unique ON stock_transactions");
        }

        // جعل العمود قابلًا لأن يكون NULL (لا نحذف العمود حفاظًا على البيانات)
        DB::statement("ALTER TABLE stock_transactions MODIFY trx_no VARCHAR(32) NULL");

        // يمكن إزالة الفهارس المساعدة إن رغبت (اختياري):
        // Schema::table('stock_transactions', function (Blueprint $table) {
        //     $table->dropIndex('stock_transactions_trx_date_index');
        //     $table->dropIndex('stock_transactions_warehouse_to_id_index');
        // });
    }
};

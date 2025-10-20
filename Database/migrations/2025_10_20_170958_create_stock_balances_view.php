<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared("
        CREATE OR REPLACE VIEW stock_balances_v AS
        SELECT
            t.warehouse_id,
            t.product_id,
            t.uom,
            SUM(t.qty) AS onhand
        FROM (
            -- وارد إلى المخزن (يزوّد الرصيد)
            SELECT
                st.warehouse_to_id AS warehouse_id,
                stl.product_id,
                stl.uom,
                CAST(stl.qty AS DECIMAL(24,4)) AS qty
            FROM stock_transaction_lines stl
            JOIN stock_transactions st ON st.id = stl.stock_transaction_id
            WHERE st.warehouse_to_id IS NOT NULL

            UNION ALL

            -- صادر من المخزن (يقلّل الرصيد)
            SELECT
                st.warehouse_from_id AS warehouse_id,
                stl.product_id,
                stl.uom,
                CAST(-stl.qty AS DECIMAL(24,4)) AS qty
            FROM stock_transaction_lines stl
            JOIN stock_transactions st ON st.id = stl.stock_transaction_id
            WHERE st.warehouse_from_id IS NOT NULL
        ) t
        GROUP BY t.warehouse_id, t.product_id, t.uom
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP VIEW IF EXISTS stock_balances_v");
    }
};

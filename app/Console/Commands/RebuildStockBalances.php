<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RebuildStockBalances extends Command
{
    protected $signature = 'inventory:rebuild-balances';
    protected $description = 'Rebuild stock_balances from stock transactions';

    public function handle(): int
    {
        $this->info('Truncating stock_balances…');
        DB::table('stock_balances')->truncate();

        $this->info('Rebuilding inbound…');
        $in = DB::table('stock_transaction_lines as stl')
            ->join('stock_transactions as st', 'st.id', '=', 'stl.stock_transaction_id')
            ->whereNotNull('st.warehouse_to_id')
            ->selectRaw('st.warehouse_to_id as warehouse_id, stl.product_id, stl.uom, SUM(stl.qty) as qty, MAX(st.id) as last_trx_id, MAX(st.trx_date) as last_trx_date')
            ->groupBy('st.warehouse_to_id','stl.product_id','stl.uom')->get();

        foreach ($in as $r) {
            DB::statement("
                INSERT INTO stock_balances (warehouse_id, product_id, uom, onhand, last_trx_id, last_trx_date, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                    onhand = onhand + VALUES(onhand),
                    last_trx_id = GREATEST(COALESCE(last_trx_id,0), VALUES(last_trx_id)),
                    last_trx_date = GREATEST(COALESCE(last_trx_date,'0000-00-00'), VALUES(last_trx_date)),
                    updated_at = NOW()
            ", [$r->warehouse_id, $r->product_id, $r->uom, $r->qty, $r->last_trx_id, $r->last_trx_date]);
        }

        $this->info('Rebuilding outbound…');
        $out = DB::table('stock_transaction_lines as stl')
            ->join('stock_transactions as st', 'st.id', '=', 'stl.stock_transaction_id')
            ->whereNotNull('st.warehouse_from_id')
            ->selectRaw('st.warehouse_from_id as warehouse_id, stl.product_id, stl.uom, SUM(stl.qty) as qty, MAX(st.id) as last_trx_id, MAX(st.trx_date) as last_trx_date')
            ->groupBy('st.warehouse_from_id','stl.product_id','stl.uom')->get();

        foreach ($out as $r) {
            DB::statement("
                INSERT INTO stock_balances (warehouse_id, product_id, uom, onhand, last_trx_id, last_trx_date, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                    onhand = onhand - VALUES(onhand),
                    last_trx_id = GREATEST(COALESCE(last_trx_id,0), VALUES(last_trx_id)),
                    last_trx_date = GREATEST(COALESCE(last_trx_date,'0000-00-00'), VALUES(last_trx_date)),
                    updated_at = NOW()
            ", [$r->warehouse_id, $r->product_id, $r->uom, $r->qty, $r->last_trx_id, $r->last_trx_date]);
        }

        $this->info('Done.');
        return self::SUCCESS;
    }
}

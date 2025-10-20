<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use App\models\inventory\warehouse as Warehouse;
use App\models\product\product     as Product;

class StockBalanceController extends Controller
{
    /** يبني تعبير SQL لاستخراج الاسم من JSON حسب اللغة مع سقوط احتياطي */
    protected function jsonNameExpr(string $col, string $lang): string
    {
        // COALESCE(JSON_UNQUOTE(JSON_EXTRACT(col, '$."ar"')), col)
        $path = "\$.$lang";
        return "COALESCE(JSON_UNQUOTE(JSON_EXTRACT($col, '$path')), $col)";
    }

    public function index(Request $req)
    {
        $tWh = (new Warehouse)->getTable();
        $tPr = (new Product  )->getTable();

        $wid = $req->integer('warehouse_id');
        $pid = $req->integer('product_id');
        $q   = trim((string)$req->get('q', ''));

        // لغة الواجهة (نختار ar أو en وإلا ar افتراضي)
        $lang = in_array(app()->getLocale(), ['ar','en']) ? app()->getLocale() : 'ar';

        $wName = $this->jsonNameExpr('w.name', $lang);
        $pName = $this->jsonNameExpr('p.name', $lang);

        if (Schema::hasTable('stock_balances')) {
            $query = DB::table('stock_balances as b')
                ->join("$tWh as w", 'w.id', '=', 'b.warehouse_id')
                ->join("$tPr as p", 'p.id', '=', 'b.product_id')
                ->selectRaw("b.warehouse_id, $wName as warehouse_name, b.product_id, $pName as product_name, b.uom, b.onhand, b.updated_at as ts")
                ->when($wid, fn($qq) => $qq->where('b.warehouse_id', $wid))
                ->when($pid, fn($qq) => $qq->where('b.product_id',   $pid))
                ->when($q, function($qq) use ($q, $wName, $pName) {
                    $qq->whereRaw("($wName LIKE ? OR $pName LIKE ? OR b.uom LIKE ?)", ["%$q%","%$q%","%$q%"]);
                })
                ->orderBy('warehouse_name')->orderBy('product_name')->orderBy('b.uom');
        } elseif (Schema::hasTable('stock_balances_v')) {
            $query = DB::table('stock_balances_v as b')
                ->join("$tWh as w", 'w.id', '=', 'b.warehouse_id')
                ->join("$tPr as p", 'p.id', '=', 'b.product_id')
                ->selectRaw("b.warehouse_id, $wName as warehouse_name, b.product_id, $pName as product_name, b.uom, b.onhand, NULL as ts")
                ->when($wid, fn($qq) => $qq->where('b.warehouse_id', $wid))
                ->when($pid, fn($qq) => $qq->where('b.product_id',   $pid))
                ->when($q, function($qq) use ($q, $wName, $pName) {
                    $qq->whereRaw("($wName LIKE ? OR $pName LIKE ? OR b.uom LIKE ?)", ["%$q%","%$q%","%$q%"]);
                })
                ->orderBy('warehouse_name')->orderBy('product_name')->orderBy('b.uom');
        } else {
            $query = null;
        }

        $balances  = $query ? $query->paginate(20)->withQueryString() : collect();
        $warehouses = Warehouse::orderBy('name')->get(['id','name']);
        $products   = Product  ::orderBy('name')->limit(500)->get(['id','name']);

        return view('inventory.stock_balance.index', compact('balances','warehouses','products','wid','pid','q'));
    }

    public function rebuild(Request $req)
    {
        if (!Schema::hasTable('stock_balances')) {
            return back()->with('error', __('pos.balance_missing_table'));
        }

        DB::beginTransaction();
        try {
            DB::table('stock_balances')->truncate();

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

            DB::commit();
            return back()->with('success', __('pos.balance_rebuilt_ok'));

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', __('pos.balance_rebuilt_fail').': '.$e->getMessage());
        }
    }
}

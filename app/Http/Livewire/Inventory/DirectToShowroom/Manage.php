<?php

namespace App\Http\Livewire\Inventory\DirectToShowroom;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

// فقط الموديلات الأربعة (للتحقق والقوائم)
use App\models\inventory\warehouse as Warehouse;
use App\models\supplier\supplier   as Supplier;
use App\models\product\product     as Product;
use App\models\unit\unit           as Unit;

class Manage extends Component
{
    // ===== بيانات الهيدر =====
    public $movement_date;
    public $warehouse_id;   // يذهب إلى warehouse_to_id (إضافة للمخزن = وجهة)
    public $supplier_id;    // (اختياري) يحفظ كـ ref_id + ref_type
    public $invoice_no;     // واجهة فقط
    public $manual_ref;     // واجهة فقط
    public $notes;

    // ===== تفاصيل الأصناف =====
    public $rows = []; // [{product_id, unit_id, qty, ...}]

    // قوائم
    public $warehouses = [];
    public $suppliers  = [];
    public $products   = [];
    public $units      = [];

    /* ========================= Validation ========================= */
    protected function rules()
    {
        $whRule   = Rule::exists((new Warehouse)->getTable(), 'id');
        $supRule  = Rule::exists((new Supplier )->getTable(), 'id');
        $prodRule = Rule::exists((new Product  )->getTable(), 'id');
        $unitRule = Rule::exists((new Unit     )->getTable(), 'id');

        return [
            'movement_date'            => ['required','date'],
            'warehouse_id'             => ['required', $whRule],
            'supplier_id'              => ['nullable', $supRule],
            'invoice_no'               => ['nullable','string','max:50'], // غير محفوظ
            'manual_ref'               => ['nullable','string','max:50'], // غير محفوظ
            'notes'                    => ['nullable','string','max:2000'],

            'rows'                     => ['required','array','min:1'],
            'rows.*.product_id'        => ['required', $prodRule],
            'rows.*.unit_id'           => ['required', $unitRule],
            'rows.*.qty'               => ['required','numeric','min:0.0001'],

            // حقول واجهة فقط (غير محفوظة)
            'rows.*.expiry_date'       => ['nullable','date'],
            'rows.*.batch_no'          => ['nullable','string','max:60'],
            'rows.*.cost_price'        => ['nullable','numeric','min:0'],
            'rows.*.sale_price'        => ['nullable','numeric','min:0'],
        ];
    }

    protected $messages = [
        'movement_date.required'     => 'برجاء إدخال تاريخ الحركة',
        'warehouse_id.required'      => 'اختر المخزن',
        'rows.required'              => 'أضف صفًا واحدًا على الأقل',
        'rows.*.product_id.required' => 'اختر الصنف',
        'rows.*.unit_id.required'    => 'اختر الوحدة',
        'rows.*.qty.min'             => 'الكمية يجب أن تكون أكبر من صفر',
    ];

    public function mount()
    {
        $this->movement_date = now()->toDateString();
        $this->warehouses = Warehouse::query()->orderBy('name')->get(['id','name']);
        $this->suppliers  = Supplier ::query()->orderBy('name')->get(['id','name']);
        $this->products   = Product  ::query()->orderBy('name')->get(['id','name']);
        $this->units      = Unit     ::query()->orderBy('name')->get(['id','name']);
        $this->rows = [ $this->blankRow() ];
    }

    private function blankRow(): array
    {
        return [
            'product_id'  => null,
            'unit_id'     => null,
            'qty'         => 1,
            'expiry_date' => null,
            'batch_no'    => null,
            'cost_price'  => null,
            'sale_price'  => null,
        ];
    }

    public function addRow() { $this->rows[] = $this->blankRow(); }
    public function removeRow($index) { unset($this->rows[$index]); $this->rows = array_values($this->rows) ?: [ $this->blankRow() ]; }
    public function resetForm() { $this->mount(); }

    /* ========================= Helpers ========================= */

    // قراءة Meta عمود من information_schema (علشان ENUM/طول النص)
    private function columnMeta(string $table, string $column): ?object
    {
        return DB::table('information_schema.COLUMNS')
            ->select('DATA_TYPE','COLUMN_TYPE','CHARACTER_MAXIMUM_LENGTH','IS_NULLABLE')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', $column)
            ->first();
    }
    private function enumOptions(?object $meta): array
    {
        if (!$meta || strtolower($meta->DATA_TYPE) !== 'enum') return [];
        preg_match_all("/'((?:[^'\\\\]|\\\\.)*)'/", $meta->COLUMN_TYPE, $m);
        return array_map(fn($v) => str_replace("\\'", "'", $v), $m[1] ?? []);
    }
    private function fitString(string $table, string $column, ?string $value): ?string
    {
        $meta = $this->columnMeta($table, $column);
        if (!$value || !$meta || !$meta->CHARACTER_MAXIMUM_LENGTH) return $value;
        return mb_substr($value, 0, (int)$meta->CHARACTER_MAXIMUM_LENGTH);
    }
    private function fitValue(string $table, string $column, $preferred, array $synonyms = [])
    {
        $meta = $this->columnMeta($table, $column);
        if (!$meta) return $preferred;
        $type = strtolower($meta->DATA_TYPE);

        if ($type === 'enum') {
            $opts = $this->enumOptions($meta);
            if ($opts) {
                $lcOpts = array_map('mb_strtolower', $opts);
                $cands = array_merge((array)$preferred, $synonyms);
                foreach ($cands as $cand) {
                    $idx = array_search(mb_strtolower((string)$cand), $lcOpts, true);
                    if ($idx !== false) return $opts[$idx];
                }
                return $opts[0];
            }
        }
        if (in_array($type, ['tinyint','smallint','int','bigint','mediumint','decimal','double','float'])) {
            return 1; // رقم آمن
        }
        $val = is_array($preferred) ? ($preferred[0] ?? 'IN') : (string)$preferred;
        return $this->fitString($table, $column, $val);
    }

    private function uomLabel($unitId): string
    {
        $u = collect($this->units)->firstWhere('id', (int)$unitId);
        $label = $u->name ?? (string)$unitId;
        return $this->fitString('stock_transaction_lines','uom', $label) ?? $label;
    }

    private function makeTrxNo(string $date, string $prefix = 'IN'): string
    {
        $day  = Carbon::parse($date)->format('Ymd');
        $base = $prefix.'-'.$day.'-';

        $last = DB::table('stock_transactions')
            ->where('trx_no', 'like', $base.'%')
            ->orderBy('trx_no', 'desc')
            ->value('trx_no');

        $seq = 1;
        if ($last && preg_match('/(\d{3,})$/', $last, $m)) {
            $seq = (int) $m[1] + 1;
        }
        return $base . str_pad((string)$seq, 4, '0', STR_PAD_LEFT);
    }

    /* ========================= الحفظ + تحديث الرصيد ========================= */

    public function save()
    {
        $data = $this->validate();

        try {
            DB::transaction(function () use ($data) {

                // قيم آمنة لأعمدة ENUM / نص
                $typeVal   = $this->fitValue('stock_transactions', 'type',   'IN',      ['RECEIVE','DIRECT_ADD','PURCHASE','INBOUND']);
                $statusVal = $this->fitValue('stock_transactions', 'status', 'posted',  ['APPROVED','CONFIRMED','OPEN','SAVED','DRAFT','POSTED']);
                $refType   = !empty($data['supplier_id'])
                           ? $this->fitValue('stock_transactions', 'ref_type', 'supplier', ['vendor','sup','vend'])
                           : null;

                // رقم الحركة
                $trxNo = $this->makeTrxNo($data['movement_date'], 'IN');

                // الهيدر
                $trxId = DB::table('stock_transactions')->insertGetId([
                    'trx_no'          => $trxNo,
                    'trx_date'        => Carbon::parse($data['movement_date'])->format('Y-m-d'),
                    'warehouse_to_id' => $data['warehouse_id'], // إضافة للمخزن كوجهة
                    'type'            => $typeVal,
                    'user_id'         => auth()->id(),
                    'ref_type'        => $refType,
                    'ref_id'          => $refType ? $data['supplier_id'] : null,
                    'notes'           => $this->fitString('stock_transactions','notes', $data['notes'] ?? null),
                    'status'          => $statusVal,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);

                // السطور
                $lines = [];
                foreach ($data['rows'] as $r) {
                    $lines[] = [
                        'stock_transaction_id' => $trxId,
                        'product_id'           => $r['product_id'],
                        'qty'                  => $r['qty'],
                        'uom'                  => $this->uomLabel($r['unit_id']),
                        'reason'               => $this->fitString('stock_transaction_lines','reason', 'direct_add'),
                        'created_at'           => now(),
                        'updated_at'           => now(),
                    ];
                }
                if ($lines) {
                    DB::table('stock_transaction_lines')->insert($lines);
                }

                // ✅ تحديث الرصيد
                $this->applyBalances($trxId);
            });

            session()->flash('success', __('inventory.saved_ok') ?? 'تم الحفظ بنجاح');
            return $this->goToWarehouses();

        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'تعذّر الحفظ: '.$e->getMessage());
            return;
        }
    }

    /** تجميع وتحديث رصيد المخزن بناءً على الحركة المحفوظة */
    private function applyBalances(int $trxId): void
    {
        $trx = DB::table('stock_transactions')
            ->select('id','trx_date','warehouse_from_id','warehouse_to_id')
            ->where('id', $trxId)->first();
        if (!$trx) return;

        $lines = DB::table('stock_transaction_lines')
            ->select('product_id','qty','uom')
            ->where('stock_transaction_id', $trxId)
            ->get();

        foreach ($lines as $line) {
            // وارد إلى المخزن (to) => زيادة
            if (!empty($trx->warehouse_to_id)) {
                $this->upsertBalance(
                    (int)$trx->warehouse_to_id,
                    (int)$line->product_id,
                    (string)$line->uom,
                    (string)$line->qty,       // +qty
                    $trxId,
                    (string)$trx->trx_date
                );
            }
            // صادر من المخزن (from) => نقصان
            if (!empty($trx->warehouse_from_id)) {
                $this->upsertBalance(
                    (int)$trx->warehouse_from_id,
                    (int)$line->product_id,
                    (string)$line->uom,
                    (string)(-1 * (float)$line->qty), // -qty
                    $trxId,
                    (string)$trx->trx_date
                );
            }
        }
    }

    /** Upsert على stock_balances باستخدام UNIQUE(warehouse_id,product_id,uom) */
    private function upsertBalance(int $warehouseId, int $productId, string $uom, string $deltaQty, int $trxId, string $trxDate): void
    {
        DB::statement("
            INSERT INTO stock_balances (warehouse_id, product_id, uom, onhand, last_trx_id, last_trx_date, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE
                onhand = onhand + VALUES(onhand),
                last_trx_id = VALUES(last_trx_id),
                last_trx_date = VALUES(last_trx_date),
                updated_at = NOW()
        ", [$warehouseId, $productId, $uom, $deltaQty, $trxId, $trxDate]);
    }

    private function goToWarehouses()
    {
        foreach (['inventory.warehouses.index','warehouses.index','inventory.warehouses','warehouses.list'] as $name) {
            if (Route::has($name)) return redirect()->route($name);
        }
        $loc = app()->getLocale();
        $path = $loc ? "/$loc/inventory/warehouses" : "/inventory/warehouses";
        return redirect()->to(url($path));
    }

    public function render()
    {
        return view('livewire.inventory.direct-to-showroom.manage');
    }
}

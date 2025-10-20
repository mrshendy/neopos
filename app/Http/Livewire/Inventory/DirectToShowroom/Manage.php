<?php

namespace App\Http\Livewire\Inventory\DirectToShowroom;

use App\models\inventory\warehouse as Warehouse;
use App\models\product\product as Product;
use App\models\supplier\supplier as Supplier;
use App\models\unit\unit as Unit;
use Illuminate\Support\Carbon;
// فقط الموديلات الأربعة المطلوبة للـ lists/validation
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Manage extends Component
{
    // ===== بيانات الهيدر =====
    public $movement_date;

    public $warehouse_id;   // يُحفظ في warehouse_to_id (إضافة للمخزن = وجهة)

    public $supplier_id;    // (اختياري) ref_id + ref_type='supplier'

    public $invoice_no;     // واجهة فقط (غير محفوظ)

    public $manual_ref;     // واجهة فقط (غير محفوظ)

    public $notes;

    // ===== تفاصيل الأصناف =====
    public $rows = [];      // [{product_id, unit_id, qty, ...}]

    // قوائم
    public $warehouses = [];

    public $suppliers = [];

    public $products = [];

    public $units = [];

    /* ========================= Validation ========================= */

    protected function rules()
    {
        $whRule = Rule::exists((new Warehouse)->getTable(), 'id');
        $supRule = Rule::exists((new Supplier)->getTable(), 'id');
        $prodRule = Rule::exists((new Product)->getTable(), 'id');
        $unitRule = Rule::exists((new Unit)->getTable(), 'id');

        return [
            'movement_date' => ['required', 'date'],
            'warehouse_id' => ['required', $whRule],
            'supplier_id' => ['nullable', $supRule],
            'invoice_no' => ['nullable', 'string', 'max:50'], // غير محفوظ
            'manual_ref' => ['nullable', 'string', 'max:50'], // غير محفوظ
            'notes' => ['nullable', 'string', 'max:2000'],

            'rows' => ['required', 'array', 'min:1'],
            'rows.*.product_id' => ['required', $prodRule],
            'rows.*.unit_id' => ['required', $unitRule], // سيحوَّل إلى uom نص
            'rows.*.qty' => ['required', 'numeric', 'min:0.0001'],

            // حقول واجهة فقط (غير محفوظة لعدم وجود أعمدة لها)
            'rows.*.expiry_date' => ['nullable', 'date'],
            'rows.*.batch_no' => ['nullable', 'string', 'max:60'],
            'rows.*.cost_price' => ['nullable', 'numeric', 'min:0'],
            'rows.*.sale_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    protected $messages = [
        'movement_date.required' => 'برجاء إدخال تاريخ الحركة',
        'warehouse_id.required' => 'اختر المخزن',
        'rows.required' => 'أضف صفًا واحدًا على الأقل',
        'rows.*.product_id.required' => 'اختر الصنف',
        'rows.*.unit_id.required' => 'اختر الوحدة',
        'rows.*.qty.min' => 'الكمية يجب أن تكون أكبر من صفر',
    ];

    public function mount()
    {
        $this->movement_date = now()->toDateString();
        $this->warehouses = Warehouse::query()->orderBy('name')->get(['id', 'name']);
        $this->suppliers = Supplier::query()->orderBy('name')->get(['id', 'name']);
        $this->products = Product::query()->orderBy('name')->get(['id', 'name']);
        $this->units = Unit::query()->orderBy('name')->get(['id', 'name']);
        $this->rows = [$this->blankRow()];
    }

    private function blankRow(): array
    {
        return [
            'product_id' => null,
            'unit_id' => null,
            'qty' => 1,
            'expiry_date' => null, // غير محفوظ
            'batch_no' => null, // غير محفوظ
            'cost_price' => null, // غير محفوظ
            'sale_price' => null, // غير محفوظ
        ];
    }

    public function addRow()
    {
        $this->rows[] = $this->blankRow();
    }

    public function removeRow($index)
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows) ?: [$this->blankRow()];
    }

    public function resetForm()
    {
        $this->mount();
    }

    /* ========================= Helpers للملاءمة مع نوع العمود ========================= */

    /** معلومات عمود من information_schema */
    private function columnMeta(string $table, string $column): ?object
    {
        return DB::table('information_schema.COLUMNS')
            ->select('DATA_TYPE', 'COLUMN_TYPE', 'CHARACTER_MAXIMUM_LENGTH', 'IS_NULLABLE')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', $column)
            ->first();
    }

    /** إرجاع قائمة enum إن وُجدت */
    private function enumOptions(?object $meta): array
    {
        if (! $meta || strtolower($meta->DATA_TYPE) !== 'enum') {
            return [];
        }
        preg_match_all("/'((?:[^'\\\\]|\\\\.)*)'/", $meta->COLUMN_TYPE, $m);

        return array_map(fn ($v) => str_replace("\\'", "'", $v), $m[1] ?? []);
    }

    /** قصّ النص على أقصى طول العمود إن وُجد */
    private function fitString(string $table, string $column, ?string $value): ?string
    {
        $meta = $this->columnMeta($table, $column);
        if (! $value || ! $meta || ! $meta->CHARACTER_MAXIMUM_LENGTH) {
            return $value;
        }

        return mb_substr($value, 0, (int) $meta->CHARACTER_MAXIMUM_LENGTH);
    }

    /**
     * ملاءمة قيمة لحقل (enum/رقمي/نصي):
     * - enum: نختار أول قيمة مفضلة موجودة، وإلا أول خيار متاح.
     * - رقمي: 1 افتراضي.
     * - نصّي: نقصّها على الطول الأقصى.
     */
    private function fitValue(string $table, string $column, $preferred, array $synonyms = [])
    {
        $meta = $this->columnMeta($table, $column);
        if (! $meta) {
            return $preferred;
        }

        $type = strtolower($meta->DATA_TYPE);

        if ($type === 'enum') {
            $opts = $this->enumOptions($meta);
            if ($opts) {
                $lcOpts = array_map('mb_strtolower', $opts);
                $cands = array_merge((array) $preferred, $synonyms);
                foreach ($cands as $cand) {
                    $idx = array_search(mb_strtolower((string) $cand), $lcOpts, true);
                    if ($idx !== false) {
                        return $opts[$idx];
                    }
                }

                return $opts[0];
            }
        }

        if (in_array($type, ['tinyint', 'smallint', 'int', 'bigint', 'mediumint', 'decimal', 'double', 'float'])) {
            return 1;
        }

        // نصّي/char — قصّ القيمة
        $val = is_array($preferred) ? ($preferred[0] ?? 'IN') : (string) $preferred;

        return $this->fitString($table, $column, $val);
    }

    /** تحويل unit_id إلى نص UOM من جدول الوحدات (مع قصّ الطول لو لزم) */
    private function uomLabel($unitId): string
    {
        $u = collect($this->units)->firstWhere('id', (int) $unitId);
        $label = $u->name ?? (string) $unitId;

        return $this->fitString('stock_transaction_lines', 'uom', $label) ?? $label;
    }

    /** توليد رقم حركة فريد بالشكل IN-YYYYMMDD-0001 */
    private function makeTrxNo(string $date, string $prefix = 'IN'): string
    {
        $day = Carbon::parse($date)->format('Ymd');
        $base = $prefix.'-'.$day.'-';

        $last = DB::table('stock_transactions')
            ->where('trx_no', 'like', $base.'%')
            ->orderBy('trx_no', 'desc')
            ->value('trx_no');

        $seq = 1;
        if ($last && preg_match('/(\d{3,})$/', $last, $m)) {
            $seq = (int) $m[1] + 1;
        }

        return $base.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }

    /* ========================= الحفظ ========================= */

    public function save()
    {
        $data = $this->validate();

        try {
            DB::transaction(function () use ($data) {

                // اختيار آمن لقيم ENUM/نص للأعمدة (يمنع Data Truncated)
                $typeVal = $this->fitValue('stock_transactions', 'type', 'IN', ['RECEIVE', 'DIRECT_ADD', 'PURCHASE', 'INBOUND']);
                $statusVal = $this->fitValue('stock_transactions', 'status', 'posted', ['APPROVED', 'CONFIRMED', 'OPEN', 'SAVED', 'DRAFT', 'POSTED']);
                $refType = ! empty($data['supplier_id'])
                           ? $this->fitValue('stock_transactions', 'ref_type', 'supplier', ['vendor', 'sup', 'vend'])
                           : null;

                // رقم الحركة
                $trxNo = $this->makeTrxNo($data['movement_date'], 'IN');

                // ======= INSERT: stock_transactions (الهيدر) =======
                $trxId = DB::table('stock_transactions')->insertGetId([
                    'trx_no' => $trxNo,
                    'trx_date' => Carbon::parse($data['movement_date'])->format('Y-m-d'),
                    'warehouse_to_id' => $data['warehouse_id'],   // إضافة للمخزن كوجهة
                    'type' => $typeVal,
                    'user_id' => auth()->id(),
                    'ref_type' => $refType,
                    'ref_id' => $refType ? $data['supplier_id'] : null,
                    'notes' => $this->fitString('stock_transactions', 'notes', $data['notes'] ?? null),
                    'status' => $statusVal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // ======= INSERT: stock_transaction_lines (السطور) =======
                $lines = [];
                foreach ($data['rows'] as $r) {
                    $lines[] = [
                        'stock_transaction_id' => $trxId,
                        'product_id' => $r['product_id'],
                        'qty' => $r['qty'],
                        'uom' => $this->uomLabel($r['unit_id']),
                        'reason' => $this->fitString('stock_transaction_lines', 'reason', 'direct_add'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                if ($lines) {
                    DB::table('stock_transaction_lines')->insert($lines);
                }
            });

            session()->flash('success', __('inventory.saved_ok') ?? 'تم الحفظ بنجاح');

            return $this->goToWarehouses();

        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'تعذّر الحفظ: '.$e->getMessage());

            return;
        }
    }

    private function goToWarehouses()
    {
        foreach (['inventory.warehouses.index', 'warehouses.index', 'inventory.warehouses', 'warehouses.list'] as $name) {
            if (Route::has($name)) {
                return redirect()->route($name);
            }
        }
        $loc = app()->getLocale();
        $path = $loc ? "/$loc/inventory/warehouses" : '/inventory/warehouses';

        return redirect()->to(url($path));
    }

    public function render()
    {
        return view('livewire.inventory.direct-to-showroom.manage');
    }
}

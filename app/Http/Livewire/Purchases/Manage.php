<?php

namespace App\Http\Livewire\Purchases;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use App\models\purchases\purchase as Purchase;
use App\models\purchases\purchase_line as PurchaseLine;
use App\models\product\product as Product;
use App\models\supplier\supplier as Supplier;
use App\models\inventory\warehouse as Warehouse;
use App\models\unit\unit as Unit;

class Manage extends Component
{
    public ?int $purchase_id = null;

    public ?string $purchase_date = null;
    public ?string $delivery_date = null;
    public $warehouse_id = null;
    public $supplier_id  = null;

    public ?string $notes_ar = null;
    public ?string $notes_en = null;

    public array $rows = [];

    public array $warehouses = [];
    public array $suppliers  = [];
    public array $categories = [];
    public array $products   = [];
    public array $units      = [];

    protected $listeners = ['deleteConfirmed' => 'removeRow'];

    /* =========================
     *  Helpers
     * =======================*/
    protected function t($value): string
    {
        // حوّل أي قيمة (JSON \ Array \ String) إلى نص في لغة الواجهة
        $locale = app()->getLocale();

        if (is_array($value)) {
            return (string) ($value[$locale] ?? ($value['ar'] ?? reset($value) ?? ''));
        }

        if (is_string($value)) {
            $trim = trim($value);
            if ($trim !== '' && $trim[0] === '{') {
                $a = json_decode($value, true) ?: [];
                return (string) ($a[$locale] ?? ($a['ar'] ?? $value));
            }
            return $value;
        }

        return (string) $value;
    }

    protected function asRowStringId($id): string
    {
        return (string) ($id ?? '');
    }

    protected function blankRow(): array
    {
        return [
            'category_id' => null,
            'product_id'  => null,
            'code'        => null,
            'unit_id'     => null,
            'qty'         => null,
            'unit_price'  => null,
            'onhand'      => 0,
            'has_expiry'  => false,
            'expiry_date' => null,
            'batch_no'    => null,
        ];
    }

    /* =========================
     *  Mount / Edit loader
     * =======================*/
    public function mount(?int $purchaseId = null): void
    {
        $this->purchase_id   = $purchaseId;
        $this->purchase_date = $this->purchase_date ?: Carbon::now()->toDateString();
        $this->delivery_date = $this->delivery_date ?: null;

        // طبّع كل الأسماء إلى نصوص
        $this->warehouses = Warehouse::orderBy('name')
            ->get(['id','name'])
            ->map(fn ($w) => ['id' => $w->id, 'name' => $this->t($w->name)])
            ->toArray();

        $this->suppliers = Supplier::orderBy('name')
            ->get(['id','name'])
            ->map(fn ($s) => ['id' => $s->id, 'name' => $this->t($s->name)])
            ->toArray();

        $this->units = Unit::orderBy('name')
            ->get(['id','name'])
            ->map(fn ($u) => ['id' => $u->id, 'name' => $this->t($u->name)])
            ->toArray();

        // لو جدول الأقسام مختلف غير هذا السطر
        $this->categories = DB::table('categories')->select('id','name')->orderBy('name')->get()
            ->map(fn ($c) => ['id' => $c->id, 'name' => $this->t($c->name)])
            ->toArray();

        // المنتجات (بدون حقل code لأنّه غير موجود عندك)
        $this->products = Product::orderBy('name')
            ->get(['id','name','category_id','sku','barcode'])
            ->map(function ($p) {
                return [
                    'id'          => $p->id,
                    'name'        => $this->t($p->name),
                    'category_id' => $p->category_id,
                    'sku'         => $p->sku,
                    'barcode'     => $p->barcode,
                ];
            })->toArray();

        if ($this->purchase_id) {
            $this->loadForEdit($this->purchase_id);
        } else {
            $this->rows = [ $this->blankRow() ];
        }
    }

    protected function loadForEdit(int $id): void
    {
        $p = Purchase::with(['warehouse','supplier','lines.product','lines.unit'])->findOrFail($id);

        $this->purchase_date = $p->purchase_date ? (string)$p->purchase_date : Carbon::now()->toDateString();
        $this->delivery_date = $p->supply_date   ? (string)$p->supply_date   : null;
        $this->warehouse_id  = $p->warehouse_id;
        $this->supplier_id   = $p->supplier_id;

        $notes = is_array($p->notes) ? $p->notes : (json_decode((string)$p->notes, true) ?: []);
        $this->notes_ar = $notes['ar'] ?? null;
        $this->notes_en = $notes['en'] ?? null;

        $this->rows = [];
        foreach ($p->lines as $line) {
            $prod = $line->product;
            $code = $prod?->sku ?? $prod?->barcode ?? '';
            $this->rows[] = [
                'category_id' => $prod?->category_id,
                'product_id'  => $line->product_id,
                'code'        => $code ?: null,
                'unit_id'     => $line->unit_id,
                'qty'         => (float)$line->qty,
                'unit_price'  => (float)$line->unit_price,
                'onhand'      => $this->getOnHand($this->warehouse_id, $line->product_id),
                'has_expiry'  => !empty($line->expiry_date),
                'expiry_date' => $line->expiry_date ? (string)$line->expiry_date : null,
                'batch_no'    => $line->batch_no,
            ];
        }

        if (empty($this->rows)) {
            $this->rows = [ $this->blankRow() ];
        }
    }

    /* =========================
     *  Row ops
     * =======================*/
    public function addRow(): void
    {
        $this->rows[] = $this->blankRow();
    }

    public function removeRow(int $idx): void
    {
        if (isset($this->rows[$idx])) {
            unset($this->rows[$idx]);
            $this->rows = array_values($this->rows);
        }
    }

    public function rowCategoryChanged(int $i): void
    {
        if (!isset($this->rows[$i])) return;
        $this->rows[$i] = array_merge($this->rows[$i], [
            'product_id'  => null,
            'code'        => null,
            'unit_id'     => null,
            'qty'         => null,
            'unit_price'  => null,
            'onhand'      => 0,
            'has_expiry'  => false,
            'expiry_date' => null,
            'batch_no'    => null,
        ]);
    }

    public function rowProductChanged(int $i): void
    {
        if (!isset($this->rows[$i])) return;

        $pid = (int)($this->rows[$i]['product_id'] ?? 0);
        if (!$pid) {
            $this->rows[$i]['code']   = null;
            $this->rows[$i]['onhand'] = 0;
            return;
        }

        $p = collect($this->products)->firstWhere('id', $pid);
        $code = $p['sku'] ?? ($p['barcode'] ?? '');
        $this->rows[$i]['code']   = $code ?: null;
        $this->rows[$i]['onhand'] = $this->getOnHand($this->warehouse_id, $pid);
    }

    protected function getOnHand($warehouseId, $productId): float
    {
        $warehouseId = (int)$warehouseId;
        $productId   = (int)$productId;
        if (!$warehouseId || !$productId) return 0;

        try {
            if (Schema()->hasTable('stock_balance')) {
                return (float) (DB::table('stock_balance')
                    ->where('warehouse_id', $warehouseId)
                    ->where('product_id',   $productId)
                    ->value('onhand') ?? 0);
            }
            if (Schema()->hasTable('stock_balances')) {
                return (float) (DB::table('stock_balances')
                    ->where('warehouse_id', $warehouseId)
                    ->where('product_id',   $productId)
                    ->value('onhand') ?? 0);
            }
        } catch (\Throwable $e) {}
        return 0;
    }

    /* =========================
     *  Validation & Save
     * =======================*/
    protected function rules(): array
    {
        return [
            'purchase_date'        => ['required','date'],
            'delivery_date'        => ['nullable','date'],
            'warehouse_id'         => ['required','integer'],
            'supplier_id'          => ['required','integer'],

            'rows'                 => ['required','array','min:1'],
            'rows.*.category_id'   => ['required','integer'],
            'rows.*.product_id'    => ['required','integer'],
            'rows.*.unit_id'       => ['required','integer'],
            'rows.*.qty'           => ['required','numeric','gt:0'],
            'rows.*.unit_price'    => ['nullable','numeric','gte:0'],
            'rows.*.expiry_date'   => ['nullable','date'],
            'rows.*.batch_no'      => ['nullable','string','max:100'],
        ];
    }

    protected function messages(): array
    {
        return [
            'purchase_date.required'      => __('pos.v_required'),
            'warehouse_id.required'       => __('pos.v_required'),
            'supplier_id.required'        => __('pos.v_required'),
            'rows.required'               => __('pos.v_at_least_one_row'),
            'rows.*.qty.gt'               => __('pos.v_gt_zero'),
        ];
    }

    public function save()
    {
        $this->validate();

        $grand = 0;
        foreach ($this->rows as $r) {
            $q = (float)($r['qty'] ?? 0);
            $u = (float)($r['unit_price'] ?? 0);
            $grand += ($q * $u);
        }

        DB::transaction(function () use ($grand) {
            if ($this->purchase_id) {
                $p = Purchase::lockForUpdate()->findOrFail($this->purchase_id);
            } else {
                $p = new Purchase();
                $p->purchase_no = $this->generateNo();
                $p->status      = 'approved';
            }

            $p->purchase_date = $this->purchase_date;
            $p->supply_date   = $this->delivery_date;
            $p->warehouse_id  = $this->warehouse_id;
            $p->supplier_id   = $this->supplier_id;
            $p->notes         = json_encode([
                'ar' => $this->notes_ar,
                'en' => $this->notes_en,
            ], JSON_UNESCAPED_UNICODE);

            $p->subtotal    = $grand;
            $p->discount    = 0;
            $p->tax         = 0;
            $p->grand_total = $grand;
            $p->save();

            if ($this->purchase_id) {
                PurchaseLine::where('purchase_id', $p->id)->delete();
            }

            foreach ($this->rows as $r) {
                $line = new PurchaseLine();
                $line->purchase_id = $p->id;
                $line->product_id  = (int)$r['product_id'];
                $line->unit_id     = (int)$r['unit_id'];
                $line->qty         = (float)$r['qty'];
                $line->unit_price  = (float)($r['unit_price'] ?? 0);
                $line->expiry_date = !empty($r['has_expiry']) ? ($r['expiry_date'] ?: null) : null;
                $line->batch_no    = $r['batch_no'] ?: null;
                $line->save();
            }

            $this->purchase_id = $p->id;
        });

        session()->flash('success', __('pos.saved_ok'));
        return redirect()->route('purchases.index');
    }

    protected function generateNo(): string
    {
        $prefix = 'PO-'.Carbon::now()->format('Ymd').'-';
        $lastId = (int)(DB::table('purchases')->max('id') ?? 0);
        return $prefix.str_pad((string)($lastId+1), 5, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        return view('livewire.purchases.manage');
    }
}

if (!function_exists('Schema')) {
    function Schema() { return app('db.schema'); }
}

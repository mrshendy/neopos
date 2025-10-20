<?php

namespace App\Http\Livewire\Purchases;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

use App\models\purchases\purchases as Purchase;
use App\models\purchases\purchase_line as Line;
use App\models\inventory\warehouse as Warehouse;
use App\models\supplier\supplier   as Supplier;
use App\models\product\product     as Product;
use App\models\unit\unit           as Unit;

class Manage extends Component
{
    // Header
    public $purchase_date, $delivery_date, $warehouse_id, $supplier_id, $notes_ar, $notes_en;

    // Rows
    public $rows = [];

    // lists
    public $warehouses, $suppliers, $products, $units;

    protected $listeners = ['deleteRowConfirmed' => 'removeRow'];

    public function mount()
    {
        $this->purchase_date = now()->toDateString();
        $this->delivery_date = null;

        $this->warehouses = Warehouse::orderBy('name')->get(['id','name']);
        $this->suppliers  = Supplier ::orderBy('name')->get(['id','name']);
        $this->products   = Product  ::orderBy('name')->get(['id','name','category_id','code']);
        $this->units      = Unit     ::orderBy('name')->get(['id','name']);

        $this->rows = [ $this->blankRow() ];
    }

    private function blankRow(): array
    {
        return [
            'product_id' => null,
            'code'       => null,
            'category'   => null,
            'unit_id'    => null,
            'uom'        => null,
            'qty'        => 1,
            'unit_price' => null,
            'expiry_date'=> null,
            'batch_no'   => null,
            'onhand'     => 0,
        ];
    }

    // عندما يختار صنف، عبّي الكود / الفئة
    public function updatedRows($value, $name)
    {
        // $name = "0.product_id" مثلاً
        if (preg_match('/^(\d+)\.product_id$/', $name, $m)) {
            $i = (int)$m[1];
            $pid = (int)($this->rows[$i]['product_id'] ?? 0);
            $p = collect($this->products)->firstWhere('id', $pid);
            if ($p) {
                $this->rows[$i]['code']     = $p->code ?? null;
                $this->rows[$i]['category'] = $this->resolveCategoryName($p->category_id ?? null);
            }
        }
        if (preg_match('/^(\d+)\.(unit_id|qty)$/', $name, $m)) {
            $i = (int)$m[1];
            $this->rows[$i]['uom'] = $this->uomLabel($this->rows[$i]['unit_id']);
            $this->rows[$i]['onhand'] = $this->calcOnHand(
                $this->warehouse_id, $this->rows[$i]['product_id'], $this->rows[$i]['uom']
            );
        }
    }

    private function resolveCategoryName($categoryId)
    {
        // إن كان عندك موديل category، استبدلها باستعلام فعلي.
        return $categoryId ? __('pos.category').' #'.$categoryId : '—';
    }

    private function uomLabel($unitId): ?string
    {
        $u = collect($this->units)->firstWhere('id', (int)$unitId);
        $label = $u->name ?? null;

        // لو الاسم JSON متعدد لغات
        if (is_string($label) && str_starts_with(trim($label), '{')) {
            $arr = json_decode($label, true) ?: [];
            $label = $arr[app()->getLocale()] ?? $arr['ar'] ?? $label;
        }
        return $label;
    }

    private function calcOnHand($warehouseId, $productId, $uom): float
    {
        if (!$warehouseId || !$productId) return 0.0;

        if (\Illuminate\Support\Facades\Schema::hasTable('stock_balances')) {
            $v = DB::table('stock_balances')->where([
                'warehouse_id' => $warehouseId,
                'product_id'   => $productId,
                'uom'          => $uom,
            ])->value('onhand');
            return (float)($v ?? 0);
        }
        return 0.0;
    }

    protected function rules()
    {
        $wh = Rule::exists((new Warehouse)->getTable(),'id');
        $sp = Rule::exists((new Supplier )->getTable(),'id');
        $pr = Rule::exists((new Product  )->getTable(),'id');
        $un = Rule::exists((new Unit     )->getTable(),'id');

        return [
            'purchase_date' => ['required','date'],
            'delivery_date' => ['nullable','date','after_or_equal:purchase_date'],
            'warehouse_id'  => ['required',$wh],
            'supplier_id'   => ['required',$sp],
            'notes_ar'      => ['nullable','string','max:2000'],
            'notes_en'      => ['nullable','string','max:2000'],

            'rows'                 => ['required','array','min:1'],
            'rows.*.product_id'    => ['required',$pr],
            'rows.*.unit_id'       => ['required',$un],
            'rows.*.qty'           => ['required','numeric','min:0.0001'],
            'rows.*.unit_price'    => ['nullable','numeric','min:0'],
            'rows.*.expiry_date'   => ['nullable','date'],
            'rows.*.batch_no'      => ['nullable','string','max:60'],
        ];
    }

    protected $messages = [
        'purchase_date.required'   => 'برجاء إدخال تاريخ الشراء',
        'warehouse_id.required'    => 'اختر المخزن',
        'supplier_id.required'     => 'اختر المورد',
        'rows.required'            => 'أضف صنفًا واحدًا على الأقل',
        'rows.*.product_id.required' => 'اختر الصنف',
        'rows.*.unit_id.required'  => 'اختر الوحدة',
        'rows.*.qty.min'           => 'الكمية يجب أن تكون أكبر من صفر',
    ];

    public function addRow(){ $this->rows[] = $this->blankRow(); }

    public function removeRow($index){
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows) ?: [ $this->blankRow() ];
    }

    public function save()
    {
        $data = $this->validate();

        DB::transaction(function () use ($data) {
            $code = $this->makeCode($data['purchase_date']);

            $purchaseId = Purchase::query()->insertGetId([
                'code'          => $code,
                'supplier_id'   => $data['supplier_id'],
                'warehouse_id'  => $data['warehouse_id'],
                'purchase_date' => $data['purchase_date'],
                'delivery_date' => $data['delivery_date'],
                'status'        => 'draft',
                'notes'         => ['ar'=>($data['notes_ar']??null),'en'=>($data['notes_en']??null)],
                'created_at'    => now(), 'updated_at' => now(),
            ]);

            $lines = [];
            foreach ($data['rows'] as $r){
                $uom = $this->uomLabel($r['unit_id']);
                $lines[] = [
                    'purchase_id'      => $purchaseId,
                    'product_id'       => $r['product_id'],
                    'category_id'      => null, // استبدلها لو عندك جدول الفئات
                    'unit_id'          => $r['unit_id'],
                    'uom'              => $uom,
                    'qty'              => $r['qty'],
                    'unit_price'       => $r['unit_price'] ?? null,
                    'batch_no'         => $r['batch_no'] ?? null,
                    'expiry_date'      => $r['expiry_date'] ?? null,
                    'onhand_snapshot'  => $this->calcOnHand($data['warehouse_id'],$r['product_id'],$uom),
                    'created_at'       => now(), 'updated_at' => now(),
                ];
            }
            if ($lines) Line::query()->insert($lines);
        });

        session()->flash('success', __('pos.saved_ok'));
        return redirect()->route('purchases.index');
    }

    private function makeCode($date, $prefix='PO'): string
    {
        $day = Carbon::parse($date)->format('Ymd');
        $base = "$prefix-$day-";
        $last = Purchase::query()->where('code','like',$base.'%')->orderBy('code','desc')->value('code');
        $seq = 1;
        if ($last && preg_match('/(\d{3,})$/',$last,$m)) $seq = (int)$m[1]+1;
        return $base . str_pad((string)$seq, 4, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        return view('livewire.purchases.manage');
    }
}

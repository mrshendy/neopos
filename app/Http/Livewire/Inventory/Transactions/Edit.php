<?php

namespace App\Http\Livewire\Inventory\Transactions;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\models\inventory\warehouse       as Warehouse;
use App\models\product\product           as Product;
use App\models\unit\unit                 as Unit;
use App\models\inventory\stock_transaction      as StockTransaction;
use App\models\inventory\stock_transaction_line as StockTransactionLine;

class Edit extends Component
{
    public int $trx_id;

    public $trx_no;
    public $trx_date;
    public $type = 'in';
    public $warehouse_from_id = null;
    public $warehouse_to_id   = null;
    public $notes = null;
    public $status = 'posted';

    public $rows = []; // product_id, unit_id, uom, qty, expiry_date, batch_no, reason, onhand

    protected $listeners = ['deleteConfirmed' => 'removeRow'];

    public function mount(int $id)
    {
        $this->trx_id = $id;
        $trx = StockTransaction::with('lines')->findOrFail($id);

        $this->trx_no  = $trx->trx_no;
        $this->trx_date = optional($trx->trx_date)->format('Y-m-d');
        $this->type    = $trx->type;
        $this->warehouse_from_id = $trx->warehouse_from_id;
        $this->warehouse_to_id   = $trx->warehouse_to_id;
        $this->notes   = $trx->notes;
        $this->status  = $trx->status;

        $this->rows = [];
        foreach ($trx->lines as $ln){
            $this->rows[] = [
                'product_id'  => $ln->product_id,
                'unit_id'     => $ln->unit_id,
                'uom'         => $ln->uom ?: '',
                'qty'         => (float)$ln->qty,
                'expiry_date' => optional($ln->expiry_date)->format('Y-m-d'),
                'batch_no'    => $ln->batch_no,
                'reason'      => $ln->reason,
                'onhand'      => 0, // سيتم تحديثه
            ];
        }
        $this->refreshAllOnhand();
    }

    private function blankRow(): array
    {
        return [
            'product_id'  => '',
            'unit_id'     => '',
            'uom'         => '',
            'qty'         => null,
            'expiry_date' => null,
            'batch_no'    => null,
            'reason'      => null,
            'onhand'      => 0,
        ];
    }

    public function addRow(){ $this->rows[] = $this->blankRow(); }
    public function removeRow($i){ unset($this->rows[$i]); $this->rows = array_values($this->rows); }

    public function updatedType()            { $this->refreshAllOnhand(); }
    public function updatedWarehouseFromId() { $this->refreshAllOnhand(); }
    public function updatedWarehouseToId()   { $this->refreshAllOnhand(); }

    public function rules()
    {
        $allowed = ['in','out','transfer','direct_add'];
        return [
            'trx_date' => ['required','date'],
            'type'     => ['required', Rule::in($allowed)],
            'warehouse_from_id' => Rule::requiredIf(fn()=> in_array($this->type,['out','transfer'])),
            'warehouse_to_id'   => Rule::requiredIf(fn()=> in_array($this->type,['in','transfer','direct_add'])),
            'notes'    => ['nullable'],
            'status'   => ['required', Rule::in(['draft','posted','cancelled'])],

            'rows' => ['required','array','min:1'],
            'rows.*.product_id'  => ['required','integer','min:1'],
            'rows.*.unit_id'     => ['nullable','integer','min:1'],
            'rows.*.uom'         => ['nullable','string','max:30'],
            'rows.*.qty'         => ['required','numeric'],
            'rows.*.expiry_date' => ['nullable','date'],
            'rows.*.batch_no'    => ['nullable','string','max:64'],
            'rows.*.reason'      => ['nullable','string','max:64'],
        ];
    }

    public function messages()
    {
        return [
            'trx_date.required' => 'يرجى تحديد تاريخ الحركة',
            'rows.required'     => 'أضِف على الأقل صفًا واحدًا',
            'rows.*.product_id.required' => 'اختر الصنف',
            'rows.*.qty.required'        => 'أدخل الكمية',
        ];
    }

    private function effectiveWarehouseForOnhand(): ?int
    {
        return match ($this->type) {
            'in','direct_add' => (int)($this->warehouse_to_id ?? 0),
            'out','transfer'  => (int)($this->warehouse_from_id ?? 0),
            default => null,
        };
    }

    private function getOnhand(?int $warehouseId, int $productId, string $uom=''): float
    {
        if (!$warehouseId || !$productId) return 0.0;
        if (!DB::getSchemaBuilder()->hasTable('stock_balances')) return 0.0;
        $q = DB::table('stock_balances')->where('warehouse_id',$warehouseId)->where('product_id',$productId);
        if ($uom) $q->where('uom',$uom);
        $row = $q->select('onhand')->first();
        return (float)($row->onhand ?? 0);
    }

    public function refreshOnhand($i)
    {
        $wid = $this->effectiveWarehouseForOnhand();
        $pid = (int)($this->rows[$i]['product_id'] ?? 0);
        $uom = (string)($this->rows[$i]['uom'] ?? '');
        $this->rows[$i]['onhand'] = $this->getOnhand($wid, $pid, $uom);
    }
    public function refreshAllOnhand(){ foreach(array_keys($this->rows) as $i) $this->refreshOnhand($i); }

    public function save()
    {
        $data = $this->validate();

        if ($this->type === 'transfer' && (int)$this->warehouse_from_id === (int)$this->warehouse_to_id) {
            $this->addError('warehouse_to_id','لا يمكن أن يكون المصدر هو نفسه الوجهة.');
            return;
        }

        DB::beginTransaction();
        try {
            $trx = StockTransaction::with('lines')->lockForUpdate()->findOrFail($this->trx_id);

            // 1) عكس تأثير الحركة القديمة من الرصيد
            foreach ($trx->lines as $ln) {
                $this->reverseFromBalance($trx->type, $trx->warehouse_from_id, $trx->warehouse_to_id, $ln->product_id, $ln->uom ?: '', (float)$ln->qty);
            }

            // 2) تحديث الهيدر
            $trx->trx_date          = $this->trx_date;
            $trx->type              = $this->type;
            $trx->warehouse_from_id = $this->warehouse_from_id ?: null;
            $trx->warehouse_to_id   = $this->warehouse_to_id   ?: null;
            $trx->notes             = is_array($this->notes) ? $this->notes : ($this->notes ?: null);
            $trx->status            = $this->status;
            $trx->save();

            // 3) حذف السطور القديمة وإنشاء الجديدة
            StockTransactionLine::where('stock_transaction_id',$trx->id)->delete();

            foreach ($this->rows as $r) {
                $line = StockTransactionLine::create([
                    'stock_transaction_id' => $trx->id,
                    'product_id'   => (int)$r['product_id'],
                    'unit_id'      => $r['unit_id'] ?: null,
                    'uom'          => $r['uom'] ?: '',
                    'qty'          => (float)$r['qty'],
                    'reason'       => $r['reason'] ?: null,
                    'expiry_date'  => $r['expiry_date'] ?: null,
                    'batch_no'     => $r['batch_no'] ?: null,
                ]);

                // 4) تطبيق تأثير الحركة الجديدة
                $this->applyToBalance($this->type, $this->warehouse_from_id, $this->warehouse_to_id, $line->product_id, $line->uom, $line->qty);
            }

            DB::commit();
            session()->flash('success','تم تحديث الحركة وإعادة ترحيل الرصيد.');
            return redirect()->route('inv.trx.index');

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            session()->flash('error','تعذّر التحديث: '.$e->getMessage());
        }
    }

    private function reverseFromBalance(string $type, $fromId, $toId, int $productId, string $uom, float $qty): void
    {
        // عكس العملية السابقة (Inverse)
        if (!DB::getSchemaBuilder()->hasTable('stock_balances')) return;

        $ins = "INSERT INTO stock_balances (warehouse_id, product_id, uom, onhand, created_at, updated_at)
                VALUES (?, ?, ?, ?, NOW(), NOW())
                ON DUPLICATE KEY UPDATE onhand = onhand + VALUES(onhand), updated_at = NOW()";

        if (in_array($type, ['in','direct_add'])) {
            if ($toId) DB::statement($ins, [(int)$toId, $productId, $uom, -$qty]); // كان +qty، نعكسه -qty
        } elseif ($type === 'out') {
            if ($fromId) DB::statement($ins, [(int)$fromId, $productId, $uom, +$qty]); // كان -qty، نعكسه +qty
        } elseif ($type === 'transfer') {
            if ($fromId) DB::statement($ins, [(int)$fromId, $productId, $uom, +$qty]); // كان -qty
            if ($toId)   DB::statement($ins, [(int)$toId,   $productId, $uom, -$qty]); // كان +qty
        }
    }

    private function applyToBalance(string $type, $fromId, $toId, int $productId, string $uom, float $qty): void
    {
        if (!DB::getSchemaBuilder()->hasTable('stock_balances')) return;

        $ins = "INSERT INTO stock_balances (warehouse_id, product_id, uom, onhand, created_at, updated_at)
                VALUES (?, ?, ?, ?, NOW(), NOW())
                ON DUPLICATE KEY UPDATE onhand = onhand + VALUES(onhand), updated_at = NOW()";

        if (in_array($type, ['in','direct_add'])) {
            if ($toId) DB::statement($ins, [(int)$toId, $productId, $uom, +$qty]);
        } elseif ($type === 'out') {
            if ($fromId) DB::statement($ins, [(int)$fromId, $productId, $uom, -$qty]);
        } elseif ($type === 'transfer') {
            if ($fromId) DB::statement($ins, [(int)$fromId, $productId, $uom, -$qty]);
            if ($toId)   DB::statement($ins, [(int)$toId,   $productId, $uom, +$qty]);
        }
    }

    public function render()
    {
        return view('livewire.inventory.transactions.edit', [
            'warehouses' => Warehouse::orderBy('name')->get(['id','name']),
            'products'   => Product::orderBy('name')->limit(500)->get(['id','name']),
            'units'      => Unit::orderBy('name')->get(['id','name']),
        ]);
    }
}

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

class Manage extends Component
{
    public $trx_date;
    public $type = 'in';
    public $warehouse_from_id = null;
    public $warehouse_to_id   = null;
    public $notes = null;

    public $rows = []; // product_id, unit_id, uom, qty, expiry_date, batch_no, reason, onhand

    protected $listeners = ['deleteConfirmed' => 'removeRow'];

    public function mount()
    {
        $this->trx_date = now()->toDateString();
        $this->rows = [ $this->blankRow() ];
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
            'type.in'           => 'نوع الحركة غير مسموح',
            'warehouse_from_id.required' => 'اختر مخزن المصدر',
            'warehouse_to_id.required'   => 'اختر مخزن الوجهة',
            'rows.required'     => 'أضِف على الأقل صفًا واحدًا',
            'rows.*.product_id.required' => 'اختر الصنف',
            'rows.*.qty.required'        => 'أدخل الكمية',
        ];
    }

    public function addRow() { $this->rows[] = $this->blankRow(); }
    public function removeRow($i){ unset($this->rows[$i]); $this->rows = array_values($this->rows); }

    public function refreshOnhand($i)
    {
        $wid = $this->effectiveWarehouseForOnhand();
        $pid = (int)($this->rows[$i]['product_id'] ?? 0);
        $uom = (string)($this->rows[$i]['uom'] ?? '');
        $this->rows[$i]['onhand'] = $this->getOnhand($wid, $pid, $uom);
    }
    public function refreshAllOnhand(){ foreach (array_keys($this->rows) as $i) $this->refreshOnhand($i); }

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

    private function nextTrxNumber(): string
    {
        $date = now()->format('Ymd');
        $prefix = 'ST-'.$date.'-';
        $last = DB::table('stock_transactions')->where('trx_no','like',$prefix.'%')->orderByDesc('id')->value('trx_no');
        $seq = 1;
        if ($last && preg_match('/-(\d{4})$/',$last,$m)) $seq = (int)$m[1]+1;
        return $prefix.str_pad((string)$seq,4,'0',STR_PAD_LEFT);
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->type === 'transfer' && (int)$this->warehouse_from_id === (int)$this->warehouse_to_id) {
            $this->addError('warehouse_to_id','لا يمكن أن يكون المصدر هو نفسه الوجهة.');
            return;
        }

        DB::beginTransaction();
        try {
            $trx = StockTransaction::create([
                'trx_no'            => $this->nextTrxNumber(),
                'trx_date'          => $this->trx_date,
                'type'              => $this->type,
                'warehouse_from_id' => $this->warehouse_from_id ?: null,
                'warehouse_to_id'   => $this->warehouse_to_id   ?: null,
                'user_id'           => auth()->id() ?: 1,
                'ref_type'          => null,
                'ref_id'            => null,
                'notes'             => is_array($this->notes) ? $this->notes : ($this->notes ?: null),
                'status'            => 'posted',
            ]);

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

                $this->applyToBalance($this->type, $this->warehouse_from_id, $this->warehouse_to_id, $line->product_id, $line->uom, $line->qty);
            }

            DB::commit();
            session()->flash('success','تم حفظ الحركة وترحيل الرصيد.');
            return redirect()->route('inv.trx.index');

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            session()->flash('error','تعذّر الحفظ: '.$e->getMessage());
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
        return view('livewire.inventory.transactions.manage', [
            'warehouses' => Warehouse::orderBy('name')->get(['id','name']),
            'products'   => Product::orderBy('name')->limit(500)->get(['id','name']),
            'units'      => Unit::orderBy('name')->get(['id','name']),
        ]);
    }
}

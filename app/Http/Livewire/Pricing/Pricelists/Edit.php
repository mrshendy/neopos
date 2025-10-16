<?php

namespace App\Http\Livewire\Pricing\Pricelists;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\models\pricing\price_list;
use App\models\pricing\price_item;
use App\models\product\product;

class Edit extends Component
{
    public $row;

    public $name = ['ar'=>'','en'=>''];
    public $valid_from = null;
    public $valid_to   = null;
    public $status     = 'active';

    public $products = [];

    protected $listeners = ['deleteConfirmed' => 'delete'];

    protected $rules = [
        'name.ar'    => 'required|string|max:255',
        'name.en'    => 'required|string|max:255',
        'valid_from' => 'nullable|date',
        'valid_to'   => 'nullable|date|after_or_equal:valid_from',
        'status'     => 'required|in:active,inactive',

        'products'                    => 'array|min:1',
        'products.*.product_id'       => 'required|exists:products,id',
        'products.*.price'            => 'required|numeric|min:0',
        'products.*.min_qty'          => 'required|integer|min:1',
        'products.*.max_qty'          => 'nullable|integer',
        'products.*.valid_from'       => 'nullable|date',
        'products.*.valid_to'         => 'nullable|date',
    ];

    private function norm($v) {
        if ($v === '' || $v === null) return '';
        return is_string($v) ? trim($v) : (string)$v;
    }

    public function mount($id): void
    {
        $this->row = price_list::findOrFail($id);

        $currentName = $this->row->name;
        if (is_string($currentName)) {
            $decoded = json_decode($currentName, true);
            $this->name = is_array($decoded)
                ? ['ar' => $decoded['ar'] ?? '', 'en' => $decoded['en'] ?? '']
                : ['ar' => (string)$currentName, 'en' => (string)$currentName];
        } elseif (is_array($currentName)) {
            $this->name = ['ar' => $currentName['ar'] ?? '', 'en' => $currentName['en'] ?? ''];
        }

        $this->valid_from = $this->row->valid_from ? (string)$this->row->valid_from : null;
        $this->valid_to   = $this->row->valid_to ? (string)$this->row->valid_to : null;
        $this->status     = $this->row->status;

        $this->products = product::orderBy('id', 'desc')->get();

        $this->products = price_item::where('price_list_id', $this->row->id)
            ->orderBy('id','asc')
            ->get(['product_id','price','min_qty','max_qty','valid_from','valid_to'])
            ->map(fn($i) => [
                'product_id' => $i->product_id,
                'price'      => $i->price,
                'min_qty'    => $i->min_qty,
                'max_qty'    => $i->max_qty,
                'valid_from' => $i->valid_from ? (string)$i->valid_from : null,
                'valid_to'   => $i->valid_to ? (string)$i->valid_to : null,
            ])->toArray();

        if (empty($this->products)) {
            $this->products = [['product_id'=>null,'price'=>null,'min_qty'=>1,'max_qty'=>null,'valid_from'=>null,'valid_to'=>null]];
        }
    }

    public function addItem(): void
    {
        $this->products[] = ['product_id'=>null,'price'=>null,'min_qty'=>1,'max_qty'=>null,'valid_from'=>null,'valid_to'=>null];
    }

    public function removeItem(int $index): void
    {
        unset($this->products[$index]);
        $this->products = array_values($this->products);
    }

    public function update()
    {
        $this->validate();

        // تحقق منطقي ومنع التكرار داخل الطلب
        foreach ($this->products as $i => $it) {
            $min = (int)($it['min_qty'] ?? 1);
            $max = $it['max_qty'] !== null && $it['max_qty'] !== '' ? (int)$it['max_qty'] : null;

            if (!is_null($max) && $max < $min) {
                $this->addError("products.$i.max_qty", 'أقصى كمية يجب أن تكون أكبر أو تساوي الحد الأدنى.');
                return;
            }
            $vf = $it['valid_from'] ?: null;
            $vt = $it['valid_to']   ?: null;
            if ($vf && $vt && $vt < $vf) {
                $this->addError("products.$i.valid_to", 'نهاية صلاحية البند يجب أن تكون بعد أو تساوي البداية.');
                return;
            }
        }

        $seen = [];
        foreach ($this->products as $i => $it) {
            $key = implode('|', [
                $this->norm($it['product_id']),
                (string)(int)($it['min_qty'] ?? 1),
                $this->norm($it['max_qty']),
                $this->norm($it['valid_from']),
                $this->norm($it['valid_to']),
            ]);
            if (isset($seen[$key])) {
                $this->addError("products.$i.product_id", 'لا يمكن تكرار نفس النطاق لنفس المنتج (نفس الكميات ونفس الفترة).');
                return;
            }
            $seen[$key] = true;
        }

        DB::beginTransaction();
        try {
            $this->row->update([
                'name'       => $this->name,
                'valid_from' => $this->valid_from ?: null,
                'valid_to'   => $this->valid_to   ?: null,
                'status'     => $this->status,
            ]);

            // إعادة بناء البنود ببساطة
            price_item::where('price_list_id', $this->row->id)->delete();

            foreach ($this->products as $it) {
                $min = (int)($it['min_qty'] ?? 1);
                $max = $it['max_qty'] !== null && $it['max_qty'] !== '' ? (int)$it['max_qty'] : null;
                $vf  = $it['valid_from'] ?: null;
                $vt  = $it['valid_to']   ?: null;

                price_item::create([
                    'price_list_id' => $this->row->id,
                    'product_id'    => (int)$it['product_id'],
                    'price'         => $it['price'],
                    'min_qty'       => $min,
                    'max_qty'       => $max,
                    'valid_from'    => $vf,
                    'valid_to'      => $vt,
                ]);
            }

            DB::commit();
            session()->flash('success', __('pos.msg_updated_ok') ?? 'تم تحديث البيانات بنجاح.');
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->addError('save', 'حدث خطأ أثناء التحديث: '.$e->getMessage());
        }
    }

    public function delete($id = null)
    {
        $targetId = $id ?? $this->row->id;

        DB::beginTransaction();
        try {
            price_item::where('price_list_id', $targetId)->delete();
            price_list::where('id', $targetId)->delete();
            DB::commit();
            session()->flash('success', __('pos.msg_deleted_ok') ?? 'تم الحذف بنجاح.');
            return redirect()->route('pricing.lists.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('error', 'تعذر الحذف: '.$e->getMessage());
            return null;
        }
    }

    public function render()
    {
        return view('livewire.pricing.pricelists.edit');
    }
}

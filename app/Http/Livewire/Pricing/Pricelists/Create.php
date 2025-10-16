<?php

namespace App\Http\Livewire\Pricing\Pricelists;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\models\pricing\price_list;
use App\models\pricing\price_item;
use App\models\product\product;

class Create extends Component
{
    public $name = ['ar'=>'','en'=>''];
    public $valid_from = null;
    public $valid_to   = null;
    public $status     = 'active';

    public $products = [];   // ['product_id','price','min_qty','max_qty','valid_from','valid_to']

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

    protected $messages = [
        'name.ar.required'        => 'اسم القائمة بالعربية مطلوب.',
        'name.en.required'        => 'اسم القائمة بالإنجليزية مطلوب.',
        'valid_to.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون بعد أو يساوي تاريخ البداية.',
        'products.min'               => 'أضف بندًا واحدًا على الأقل.',
    ];

    private function norm($v) {
        if ($v === '' || $v === null) return '';
        return is_string($v) ? trim($v) : (string)$v;
    }

    public function mount(): void
    {
        $this->products = product::orderBy('id', 'desc')->get();
        $this->products = [
            ['product_id'=>null, 'price'=>null, 'min_qty'=>1, 'max_qty'=>null, 'valid_from'=>null, 'valid_to'=>null],
        ];
    }

    public function addItem(): void
    {
        $this->products[] = ['product_id'=>null, 'price'=>null, 'min_qty'=>1, 'max_qty'=>null, 'valid_from'=>null, 'valid_to'=>null];
    }

    public function removeItem(int $index): void
    {
        unset($this->products[$index]);
        $this->products = array_values($this->products);
    }

    public function save()
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
            $pl = price_list::create([
                'name'       => $this->name,
                'valid_from' => $this->valid_from ?: null,
                'valid_to'   => $this->valid_to   ?: null,
                'status'     => $this->status,
            ]);

            foreach ($this->products as $it) {
                $min = (int)($it['min_qty'] ?? 1);
                $max = $it['max_qty'] !== null && $it['max_qty'] !== '' ? (int)$it['max_qty'] : null;
                $vf  = $it['valid_from'] ?: null;
                $vt  = $it['valid_to']   ?: null;

                price_item::create([
                    'price_list_id' => $pl->id,
                    'product_id'    => (int)$it['product_id'],
                    'price'         => $it['price'],
                    'min_qty'       => $min,
                    'max_qty'       => $max,
                    'valid_from'    => $vf,
                    'valid_to'      => $vt,
                ]);
            }

            DB::commit();
            session()->flash('success', __('pos.msg_saved_ok') ?? 'تم الحفظ بنجاح.');
            return redirect()->route('pricing.lists.edit', $pl->id);

        } catch (\Throwable $e) {
            DB::rollBack();
            $this->addError('save', 'حدث خطأ أثناء الحفظ: '.$e->getMessage());
            return null;
        }
    }

    public function render()
    {
        return view('livewire.pricing.pricelists.create');
    }
}

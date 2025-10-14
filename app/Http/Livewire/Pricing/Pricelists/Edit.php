<?php

namespace App\Http\Livewire\Pricing\Pricelists;

use Livewire\Component;
use App\models\pricing\price_list;
use App\models\pricing\price_item;
use App\models\product\product;
use App\models\product\sales_channel;
use App\models\product\customer_group;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public $list_id;

    // Header fields
    public $name = ['ar'=>'','en'=>''];
    public $sales_channel_id = null;
    public $customer_group_id = null;
    public $valid_from = null;
    public $valid_to = null;
    public $status = 'active';

    // Dropdowns & data
    public $channels = [];
    public $groups = [];
    public $products = [];

    // Items table (inline)
    public $items = []; // for display

    // Item form
    public $price_item_id = null;
    public $product_id = null;
    public $price = 0.00;
    public $min_qty = 1;
    public $max_qty = null;
    public $item_valid_from = null;
    public $item_valid_to = null;

    protected function rulesHeader()
    {
        return [
            'name.ar' => 'required|string|max:255',
            'name.en' => 'required|string|max:255',
            'sales_channel_id' => 'nullable|exists:sales_channels,id',
            'customer_group_id'=> 'nullable|exists:customer_groups,id',
            'valid_from' => 'nullable|date',
            'valid_to'   => 'nullable|date|after_or_equal:valid_from',
            'status'     => 'required|in:active,inactive',
        ];
    }

    protected $messages = [
        'name.ar.required' => 'اسم القائمة بالعربية مطلوب.',
        'name.en.required' => 'اسم القائمة بالإنجليزية مطلوب.',
        'valid_to.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون بعد أو يساوي تاريخ البداية.',
        'price.min' => 'السعر لا يمكن أن يكون أقل من 0.',
    ];

    protected function rulesItem()
    {
        return [
            'product_id' => ['required','exists:products,id',
                Rule::unique('price_items','product_id')
                    ->ignore($this->price_item_id)
                    ->where(function($q){
                        return $q->where('price_list_id',$this->list_id)
                                 ->whereNull('deleted_at');
                    })
            ],
            'price' => 'required|numeric|min:0',
            'min_qty' => 'required|integer|min:1',
            'max_qty' => 'nullable|integer|min:1|gte:min_qty',
            'item_valid_from' => 'nullable|date',
            'item_valid_to'   => 'nullable|date|after_or_equal:item_valid_from',
        ];
    }

    public function mount($id)
    {
        $row = price_list::find($id);
        if(!$row){
            session()->flash('error', __('pos.no_data'));
            return redirect()->route('pricing.lists.index');
        }

        $this->list_id = $row->id;
        $this->name = [
            'ar' => $row->getTranslation('name','ar'),
            'en' => $row->getTranslation('name','en'),
        ];
        $this->sales_channel_id = $row->sales_channel_id;
        $this->customer_group_id= $row->customer_group_id;
        $this->valid_from = $row->valid_from;
        $this->valid_to   = $row->valid_to;
        $this->status     = $row->status;

        $this->channels = sales_channel::select('id','name')->orderBy('id','desc')->get();
        $this->groups   = customer_group::select('id','name')->orderBy('id','desc')->get();
        $this->products = product::select('id','sku','name')->orderByDesc('id')->get();

        $this->reloadItems();
    }

    private function reloadItems()
    {
        $this->items = price_item::where('price_list_id',$this->list_id)
            ->orderByDesc('id')
            ->get()
            ->map(function($i){
                return [
                    'id' => $i->id,
                    'product_id' => $i->product_id,
                    'product_label' => optional($i->product)->sku.' — '.optional($i->product)?->getTranslation('name', app()->getLocale()),
                    'price' => $i->price,
                    'min_qty' => $i->min_qty,
                    'max_qty' => $i->max_qty,
                    'valid_from' => $i->valid_from,
                    'valid_to'   => $i->valid_to
                ];
            })->toArray();
    }

    /** حفظ رأس القائمة */
    public function saveHeader()
    {
        $this->validate($this->rulesHeader());

        $row = price_list::findOrFail($this->list_id);
        $row->update([
            'name' => $this->name,
            'sales_channel_id' => $this->sales_channel_id,
            'customer_group_id'=> $this->customer_group_id,
            'valid_from' => $this->valid_from,
            'valid_to'   => $this->valid_to,
            'status'     => $this->status,
        ]);

        session()->flash('success', __('pos.msg_saved_ok'));
    }

    /** تعبئة نموذج عنصر للتعديل */
    public function editItem($id)
    {
        $i = price_item::where('price_list_id',$this->list_id)->findOrFail($id);
        $this->price_item_id = $i->id;
        $this->product_id = $i->product_id;
        $this->price = $i->price;
        $this->min_qty = $i->min_qty;
        $this->max_qty = $i->max_qty;
        $this->item_valid_from = $i->valid_from;
        $this->item_valid_to   = $i->valid_to;
    }

    /** تصفير نموذج العنصر */
    public function resetItemForm()
    {
        $this->price_item_id = null;
        $this->product_id = null;
        $this->price = 0.00;
        $this->min_qty = 1;
        $this->max_qty = null;
        $this->item_valid_from = null;
        $this->item_valid_to = null;
        $this->resetValidation();
    }

    /** حفظ/تحديث عنصر السعر مع فحص التداخل */
    public function saveItem()
    {
        $this->validate($this->rulesItem());

        // فحص تداخل الفترات لنفس المنتج داخل نفس القائمة
        $from = $this->item_valid_from ?: '0000-01-01';
        $to   = $this->item_valid_to   ?: '9999-12-31';

        $overlap = price_item::where('price_list_id',$this->list_id)
            ->where('product_id',$this->product_id)
            ->when($this->price_item_id, fn($q)=>$q->where('id','!=',$this->price_item_id))
            ->where(function($q) use ($from,$to){
                $q->where(function($qq) use ($from,$to){
                    $qq->whereNull('valid_from')->orWhere('valid_from','<=',$to);
                })->where(function($qq) use ($from,$to){
                    $qq->whereNull('valid_to')->orWhere('valid_to','>=',$from);
                });
            })->exists();

        if($overlap){
            $this->addError('item_valid_from', __('pos.err_price_conflict'));
            session()->flash('error', __('pos.err_price_conflict_banner'));
            return;
        }

        if($this->price_item_id){
            $i = price_item::findOrFail($this->price_item_id);
            $i->update([
                'product_id' => $this->product_id,
                'price'      => $this->price,
                'min_qty'    => $this->min_qty,
                'max_qty'    => $this->max_qty,
                'valid_from' => $this->item_valid_from,
                'valid_to'   => $this->item_valid_to,
            ]);
        }else{
            price_item::create([
                'price_list_id' => $this->list_id,
                'product_id' => $this->product_id,
                'price'      => $this->price,
                'min_qty'    => $this->min_qty,
                'max_qty'    => $this->max_qty,
                'valid_from' => $this->item_valid_from,
                'valid_to'   => $this->item_valid_to,
            ]);
        }

        $this->resetItemForm();
        $this->reloadItems();
        session()->flash('success', __('pos.msg_saved_ok'));
    }

    /** حذف عنصر سعر */
    public function deleteItem($id)
    {
        $i = price_item::where('price_list_id',$this->list_id)->findOrFail($id);
        $i->delete();
        $this->reloadItems();
        session()->flash('success', __('pos.msg_deleted_ok'));
    }

    public function render()
    {
        return view('livewire.pricing.pricelists.edit');
    }
}

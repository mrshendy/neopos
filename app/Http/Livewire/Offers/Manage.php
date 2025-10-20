<?php

namespace App\Http\Livewire\offers;

use Livewire\Component;
use App\models\offers\offers as Offer;

class Manage extends Component
{
    public ?Offer $offer = null;

    public $name = ['ar'=>'','en'=>''];
    public $description = ['ar'=>'','en'=>''];
    public $type = 'percentage';
    public $discount_value = null;
    public $x_qty = null;
    public $y_qty = null;
    public $bundle_price = null;
    public $max_discount_per_order = null;
    public $is_stackable = true;
    public $priority = 100;
    public $policy = 'highest_priority';
    public $status = 'active';
    public $start_at = null;
    public $end_at = null;
    public $days_of_week = [];
    public $hours_from = null;
    public $hours_to = null;
    public $sales_channel = null;
    public $branch_ids = [];
    public $product_ids = [];
    public $category_ids = [];

    protected function rules(){
        $base = [
            'name.ar' => 'required|string|max:255',
            'name.en' => 'required|string|max:255',
            'description.ar' => 'nullable|string|max:2000',
            'description.en' => 'nullable|string|max:2000',
            'type' => 'required|in:percentage,fixed,bxgy,bundle',
            'is_stackable' => 'boolean',
            'priority' => 'required|integer|min:1|max:100000',
            'policy' => 'required|in:highest_priority,largest_discount',
            'status' => 'required|in:draft,active,paused,expired',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'days_of_week' => 'array',
            'days_of_week.*' => 'integer|min:1|max:7',
            'hours_from' => 'nullable|date_format:H:i',
            'hours_to' => 'nullable|date_format:H:i',
            'sales_channel' => 'nullable|string|max:20',
            'branch_ids' => 'array',
            'product_ids' => 'array',
            'category_ids' => 'array',
            'max_discount_per_order' => 'nullable|numeric|min:0',
        ];

        if (in_array($this->type, ['percentage','fixed'])) {
            $base['discount_value'] = $this->type==='percentage'
                ? 'required|numeric|min:0|max:100'
                : 'required|numeric|min:0';
        }
        if ($this->type === 'bxgy') {
            $base['x_qty'] = 'required|integer|min:1';
            $base['y_qty'] = 'required|integer|min:1';
        }
        if ($this->type === 'bundle') {
            $base['bundle_price'] = 'required|numeric|min:0';
        }
        return $base;
    }

    protected $messages = [
        'name.ar.required' => 'الاسم بالعربية مطلوب',
        'name.en.required' => 'الاسم بالإنجليزية مطلوب',
        'discount_value.required' => 'قيمة الخصم مطلوبة',
        'x_qty.required' => 'قيمة X مطلوبة في عرض BxGy',
        'y_qty.required' => 'قيمة Y مطلوبة في عرض BxGy',
        'bundle_price.required' => 'سعر الحزمة مطلوب في عرض Bundle',
        'end_at.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية',
    ];

    public function mount(?Offer $offer = null)
    {
        if ($offer && $offer->exists) {
            $this->offer = $offer;
            $this->fill($offer->only([
                'type','discount_value','x_qty','y_qty','bundle_price','max_discount_per_order',
                'is_stackable','priority','policy','status','start_at','end_at','days_of_week','hours_from','hours_to','sales_channel'
            ]));
            $this->name = $offer->getTranslations('name');
            $this->description = $offer->getTranslations('description') ?: $this->description;
            $this->branch_ids = $offer->branches()->pluck('branches.id')->toArray();
            $this->product_ids = $offer->products()->pluck('products.id')->toArray();
            $this->category_ids = $offer->categories()->pluck('categories.id')->toArray();
        }
    }

    public function save()
    {
        $data = $this->validate();
        $attrs = array_merge($data, [
            'name' => $this->name,
            'description' => $this->description,
        ]);

        if ($this->offer) {
            $this->offer->update($attrs);
            $offer = $this->offer->refresh();
        } else {
            $offer = Offer::create($attrs);
        }

        $offer->branches()->sync($this->branch_ids);
        $offer->products()->sync(collect($this->product_ids)->mapWithKeys(fn($id)=>[$id=>['min_qty'=>1]])->all());
        $offer->categories()->sync($this->category_ids);

        session()->flash('success', __('pos.msg_saved_ok'));
        return redirect()->route('offers.index');
    }

    public function render(){ return view('livewire.offers.manage'); }
}

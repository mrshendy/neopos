<?php

namespace App\Http\Livewire\coupons;

use Livewire\Component;
use App\models\offers\coupons as Coupon;

class Manage extends Component
{
    public ?Coupon $coupon = null;

    public $name=['ar'=>'','en'=>'']; public $description=['ar'=>'','en'=>''];
    public $type='percentage'; public $discount_value=null;
    public $max_uses_per_customer=1; public $max_total_uses=null; public $is_stackable=false;
    public $status='active'; public $start_at=null; public $end_at=null;
    public $branch_ids=[]; public $customer_ids=[];

    protected function rules(){
        return [
            'name.ar'=>'required|max:255','name.en'=>'required|max:255',
            'type'=>'required|in:percentage,fixed',
            'discount_value'=> $this->type==='percentage' ? 'required|numeric|min:0|max:100' : 'required|numeric|min:0',
            'max_uses_per_customer'=>'required|integer|min:1',
            'max_total_uses'=>'nullable|integer|min:1',
            'is_stackable'=>'boolean','status'=>'required|in:active,paused,expired',
            'start_at'=>'nullable|date','end_at'=>'nullable|date|after_or_equal:start_at',
            'branch_ids'=>'array','customer_ids'=>'array'
        ];
    }

    protected $messages = [
        'name.ar.required' => 'الاسم بالعربية مطلوب',
        'name.en.required' => 'الاسم بالإنجليزية مطلوب',
        'discount_value.required' => 'قيمة الخصم مطلوبة',
        'end_at.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية',
        'max_uses_per_customer.min' => 'الحد الأدنى لاستخدام العميل مرة واحدة على الأقل',
    ];

    public function mount(?Coupon $coupon=null){
        if($coupon && $coupon->exists){
            $this->coupon = $coupon;
            $this->fill($coupon->only([
                'type','discount_value','max_uses_per_customer','max_total_uses','is_stackable','status','start_at','end_at'
            ]));
            $this->name = $coupon->getTranslations('name');
            $this->description = $coupon->getTranslations('description') ?: $this->description;
            $this->branch_ids = $coupon->branches()->pluck('branches.id')->toArray();
            $this->customer_ids = $coupon->customers()->pluck('customers.id')->toArray();
        }
    }

    public function save(){
        $data = $this->validate();
        $attrs = array_merge($data, ['name'=>$this->name,'description'=>$this->description]);

        if($this->coupon){ $this->coupon->update($attrs); $coupon=$this->coupon->refresh(); }
        else { $coupon = Coupon::create($attrs); }

        $coupon->branches()->sync($this->branch_ids);
        $coupon->customers()->sync($this->customer_ids);

        session()->flash('success', __('pos.msg_saved_ok'));
        return redirect()->route('coupons.index');
    }

    public function render(){ return view('livewire.coupons.manage'); }
}

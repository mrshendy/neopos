<?php

namespace App\Http\Livewire\Pricing\Pricelists;

use Livewire\Component;
use App\models\pricing\price_list;
use App\models\product\sales_channel;
use App\models\product\customer_group;

class Create extends Component
{
    public $name = ['ar'=>'','en'=>''];
    public $sales_channel_id = null;
    public $customer_group_id = null;
    public $valid_from = null;
    public $valid_to = null;
    public $status = 'active';

    public $channels = [];
    public $groups = [];

    protected $rules = [
        'name.ar' => 'required|string|max:255',
        'name.en' => 'required|string|max:255',
        'sales_channel_id' => 'nullable|exists:sales_channels,id',
        'customer_group_id' => 'nullable|exists:customer_groups,id',
        'valid_from' => 'nullable|date',
        'valid_to'   => 'nullable|date|after_or_equal:valid_from',
        'status'     => 'required|in:active,inactive',
    ];

    protected $messages = [
        'name.ar.required' => 'اسم القائمة بالعربية مطلوب.',
        'name.en.required' => 'اسم القائمة بالإنجليزية مطلوب.',
        'valid_to.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون بعد أو يساوي تاريخ البداية.',
    ];

    public function mount()
    {
        $this->channels = sales_channel::select('id','name')->orderBy('id','desc')->get();
        $this->groups = customer_group::select('id','name')->orderBy('id','desc')->get();
    }

    public function save()
    {
        $this->validate();

        $row = price_list::create([
            'name' => $this->name,
            'sales_channel_id' => $this->sales_channel_id,
            'customer_group_id'=> $this->customer_group_id,
            'valid_from' => $this->valid_from,
            'valid_to'   => $this->valid_to,
            'status'     => $this->status,
        ]);

        session()->flash('success', __('pos.msg_saved_ok'));
        return redirect()->route('pricing.lists.edit', $row->id);
    }

    public function render()
    {
        return view('livewire.pricing.pricelists.create');
    }
}

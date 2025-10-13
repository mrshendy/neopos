<?php

namespace App\Http\Livewire\customer;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\customer\customer;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'delete'];

    // فلاتر
    public $search = '';
    public $code = '';
    public $phone = '';
    public $tax = '';
    public $channel = '';
    public $account_status = '';
    public $price_category_id = '';

    public function updating($field)
    {
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $c = customer::findOrFail($id);
        $c->account_status = $c->account_status === 'active' ? 'inactive' : 'active';
        $c->save();
        session()->flash('success', __('pos.msg_status_updated'));
    }

    public function delete($id)
    {
        // إن أردت منع حذف العميل صاحب معاملات، فعّل الكود التالي:
        // $c = customer::withCount('transactions')->findOrFail($id);
        // if ($c->transactions_count > 0) {
        //     session()->flash('error', __('pos.msg_cannot_delete_has_tx'));
        //     return;
        // }
        $c = customer::findOrFail($id);
        $c->delete();
        session()->flash('success', __('pos.msg_deleted'));
    }

    public function render()
    {
        $locale = app()->getLocale();

        $customers = customer::query()
            ->when($this->search, fn($q) =>
                $q->where("legal_name->$locale",'like',"%{$this->search}%")
                  ->orWhere("trade_name->$locale",'like',"%{$this->search}%"))
            ->when($this->code, fn($q) => $q->where('code','like',"%{$this->code}%"))
            ->when($this->phone, fn($q) => $q->where('phone','like',"%{$this->phone}%"))
            ->when($this->tax, fn($q) => $q->where('tax_number','like',"%{$this->tax}%"))
            ->when($this->channel, fn($q) => $q->where('channel',$this->channel))
            ->when($this->account_status, fn($q) => $q->where('account_status',$this->account_status))
            ->when($this->price_category_id, fn($q) => $q->where('price_category_id',$this->price_category_id))
            ->orderBy('id','desc')
            ->paginate(10);

        return view('livewire.customer.index', compact('customers'));
    }
}

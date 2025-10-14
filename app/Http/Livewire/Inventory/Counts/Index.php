<?php

namespace App\Http\Livewire\inventory\counts;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\inventory\{stock_count, warehouse};

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $warehouse_id = '';
    public $policy = 'periodic'; // periodic | spot
    public $notes = '';

    protected $rules = [
        'warehouse_id' => 'required|exists:warehouses,id',
        'policy'       => 'required|in:periodic,spot',
        'notes'        => 'nullable|string|max:2000',
    ];

    protected $messages = [
        'warehouse_id.required' => 'اختَر المخزن',
        'policy.in'             => 'نوع الجرد غير صحيح',
    ];

    public function startCount()
    {
        $this->validate();

        stock_count::create([
            'warehouse_id' => $this->warehouse_id,
            'policy'       => $this->policy,
            'started_at'   => now(),
            'status'       => 'open',
            'notes'        => $this->notes ?: null,
        ]);

        session()->flash('success', trans('pos.saved_success'));
        $this->reset(['warehouse_id','policy','notes']);
    }

    public function render()
    {
        $counts = stock_count::orderByDesc('id')->paginate(10);
        $warehouses = warehouse::orderByDesc('id')->get(['id','name']);
        return view('livewire.inventory.counts.index', compact('counts','warehouses'));
    }
}

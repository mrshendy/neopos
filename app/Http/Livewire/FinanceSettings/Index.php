<?php

namespace App\Http\Livewire\FinanceSettings;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\finance\finance_settings as Settings;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'delete'];

    // فلاتر
    public $search = '';
    public $branch_id = '';
    public $warehouse_id = '';
    public $is_available = '';
    public $perPage = 10;

    public function updating($field)
    {
        if (in_array($field, ['search','branch_id','warehouse_id','is_available','perPage'])) {
            $this->resetPage();
        }
    }

    protected function baseQuery()
    {
        return Settings::query()
            ->when($this->search !== '', function($q){
                $t = trim($this->search);
                $q->where(function($qq) use ($t){
                    $qq->where('name->ar','like',"%{$t}%")
                       ->orWhere('name->en','like',"%{$t}%")
                       ->orWhere('receipt_prefix','like',"%{$t}%");
                });
            })
            ->when($this->branch_id !== '', fn($q) => $q->where('branch_id',$this->branch_id))
            ->when($this->warehouse_id !== '', fn($q) => $q->where('warehouse_id',$this->warehouse_id))
            ->when($this->is_available !== '', fn($q) => $q->where('is_available', $this->is_available))
            ->orderByDesc('id');
    }

    public function toggleAvailable($id)
    {
        $row = Settings::findOrFail($id);
        $row->is_available = !$row->is_available;
        $row->save();
        session()->flash('success', __('pos.msg_status_toggled'));
    }

    public function delete($id)
    {
        $row = Settings::findOrFail($id);
        $row->delete();
        session()->flash('success', __('pos.msg_deleted_success'));
    }

    public function render()
    {
        $items = $this->baseQuery()->paginate($this->perPage);
        return view('livewire.finance-settings.index', compact('items'));
    }
}

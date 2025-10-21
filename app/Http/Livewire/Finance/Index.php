<?php

namespace App\Http\Livewire\finance;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\finance\finance as FinanceModel;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'delete'];

    // فلاتر
    public $search = '';
    public $status = '';
    public $branch_id = '';
    public $date_from = '';
    public $date_to   = '';
    public $perPage   = 10;

    // تنبيهات داخل الكومبوننت
    public $warning = '';

    public function updating($field)
    {
        if (in_array($field, ['search','status','branch_id','date_from','date_to','perPage'])) {
            $this->resetPage();
        }
    }

    protected function baseQuery()
    {
        return FinanceModel::query()
            ->when($this->status !== '', fn($q) => $q->where('status', $this->status))
            ->when($this->branch_id !== '', fn($q) => $q->where('branch_id', $this->branch_id))
            ->when($this->search !== '', function($q){
                $term = trim($this->search);
                // بحث في JSON (ar/en)
                $q->where(function($qq) use ($term){
                    $qq->where('name->ar', 'like', "%{$term}%")
                       ->orWhere('name->en', 'like', "%{$term}%")
                       ->orWhere('receipt_prefix', 'like', "%{$term}%");
                });
            })
            ->when($this->date_from, fn($q) => $q->whereDate('created_at', '>=', $this->date_from))
            ->when($this->date_to,   fn($q) => $q->whereDate('created_at', '<=', $this->date_to))
            ->orderByDesc('id');
    }

    public function toggleStatus($id)
    {
        $row = FinanceModel::findOrFail($id);
        $row->status = $row->status === 'active' ? 'inactive' : 'active';
        $row->save();

        session()->flash('success', __('pos.msg_status_toggled'));
    }

    public function confirmDelete($id) { /* handled in blade via JS */ }

    public function delete($id)
    {
        $row = FinanceModel::findOrFail($id);
        $row->delete();
        session()->flash('success', __('pos.msg_deleted_success'));
    }

    public function render()
    {
        $items = $this->baseQuery()->paginate($this->perPage);
        return view('livewire.finance.index', compact('items'));
    }
}

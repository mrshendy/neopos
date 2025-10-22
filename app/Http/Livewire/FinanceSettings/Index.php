<?php
// app/Http/Livewire/FinanceSettings/Index.php

namespace App\Http\Livewire\FinanceSettings;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\finance\finance_settings as Settings;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filters
    public $q = '';
    public $type = 'all';        // all | main | sub
    public $available = 'all';   // all | 1 | 0
    public $trashed = 'active';  // active | with | only
    public $perPage = 10;

    // Sorting
    public $sortField = 'id';
    public $sortDirection = 'desc';

    protected $listeners = ['deleteConfirmed' => 'delete'];

    public function updatingQ() { $this->resetPage(); }
    public function updatedType() { $this->resetPage(); }
    public function updatedAvailable() { $this->resetPage(); }
    public function updatedTrashed() { $this->resetPage(); }
    public function updatedPerPage() { $this->resetPage(); }

    public function setSort($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $row = Settings::withTrashed()->findOrFail($id);
        $row->is_available = !$row->is_available;
        $row->save();

        session()->flash('success', __('pos.msg_status_toggled'));
    }

    public function delete($id)
    {
        $row = Settings::findOrFail($id);
        $row->delete(); // soft delete
        session()->flash('success', __('pos.msg_deleted_success'));
        $this->resetPage();
    }

    public function restore($id)
    {
        $row = Settings::onlyTrashed()->findOrFail($id);
        $row->restore();
        session()->flash('success', __('pos.msg_updated_success'));
    }

    public function clearFilters()
    {
        $this->q = '';
        $this->type = 'all';
        $this->available = 'all';
        $this->trashed = 'active';
        $this->perPage = 10;
        $this->sortField = 'id';
        $this->sortDirection = 'desc';
        $this->resetPage();
    }

    public function getRowsProperty()
    {
        $locale = app()->getLocale();

        $query = Settings::query();

        // trashed scope
        if ($this->trashed === 'with') $query->withTrashed();
        elseif ($this->trashed === 'only') $query->onlyTrashed();

        // search on translated name (JSON: name->ar / name->en)
        if (trim($this->q) !== '') {
            $q = '%' . trim($this->q) . '%';
            $query->where(function ($qBuilder) use ($q) {
                $qBuilder->where('name->ar', 'like', $q)
                         ->orWhere('name->en', 'like', $q)
                         ->orWhere('receipt_prefix', 'like', $q);
            });
        }

        // filter type
        if ($this->type === 'main' || $this->type === 'sub') {
            $query->where('cashbox_type', $this->type);
        }

        // filter availability
        if ($this->available === '1') {
            $query->where('is_available', true);
        } elseif ($this->available === '0') {
            $query->where('is_available', false);
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.finance-settings.index', [
            'rows' => $this->rows,
            'locale' => app()->getLocale(),
        ]);
    }
}

<?php

namespace App\Http\Livewire\Country;

use App\models\Country;
use Livewire\Component;
use Livewire\WithPagination;

class Manage extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 10;

    public $showModal = false;

    public $editingId = null;

    public $name_ar = '';

    public $name_en = '';

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['deleteConfirmed' => 'delete'];

    protected function rules()
    {
        return [
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->reset(['editingId', 'name_ar', 'name_en']);
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $row = Country::findOrFail($id);
        $this->editingId = $row->id;
        $this->name_ar = $row->getTranslation('name', 'ar');
        $this->name_en = $row->getTranslation('name', 'en');
        $this->showModal = true;
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->editingId) {
            $row = Country::findOrFail($this->editingId);
            $row->setTranslations('name', ['ar' => $data['name_ar'], 'en' => $data['name_en']]);
            $row->save();
        } else {
            Country::create([
                'name' => ['ar' => $data['name_ar'], 'en' => $data['name_en']],
                'status' => 'active',
            ]);
        }

        $this->showModal = false;
        session()->flash('success', __('Saved successfully'));
    }

    public function delete($id)
    {
        Country::findOrFail($id)->delete();
        session()->flash('success', __('Deleted successfully'));
    }

    public function getRowsProperty()
    {
        $q = Country::query();
        if (filled($this->search)) {
            $s = "%{$this->search}%";
            $q->where(fn ($qq) => $qq->where('name->ar', 'like', $s)->orWhere('name->en', 'like', $s));
        }

        return $q->orderByDesc('id')->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.country.manage', ['rows' => $this->rows])
            ->layout('layouts.master');
    }
}

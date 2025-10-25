<?php

namespace App\Http\Livewire\Governorate;

use App\models\country;
use App\models\governorate;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Manage extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 10;

    public $filterCountry = '';

    public $showModal = false;

    public $editingId = null;

    public $name_ar = '';

    public $name_en = '';

    public $id_country = '';

    public $countries = [];

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['deleteConfirmed' => 'delete'];

    public function mount()
    {
        $this->countries = Country::select('id', 'name')->orderBy('name')->get();
    }

    protected function rules()
    {
        return [
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'id_country' => ['required', 'integer', Rule::exists((new Country)->getTable(), 'id')],
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterCountry()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->reset(['editingId', 'name_ar', 'name_en', 'id_country']);
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $row = Governorate::findOrFail($id);
        $this->editingId = $row->id;
        $this->name_ar = $row->getTranslation('name', 'ar');
        $this->name_en = $row->getTranslation('name', 'en');
        $this->id_country = $row->id_country;
        $this->showModal = true;
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->editingId) {
            $row = Governorate::findOrFail($this->editingId);
            $row->setTranslations('name', ['ar' => $data['name_ar'], 'en' => $data['name_en']]);
            $row->id_country = $data['id_country'];
            $row->save();
        } else {
            Governorate::create([
                'name' => ['ar' => $data['name_ar'], 'en' => $data['name_en']],
                'id_country' => $data['id_country'],
                'status' => 'active',
            ]);
        }

        $this->showModal = false;
        session()->flash('success', __('Saved successfully'));
    }

    public function delete($id)
    {
        Governorate::findOrFail($id)->delete();
        session()->flash('success', __('Deleted successfully'));
    }

    public function getRowsProperty()
    {
        $q = Governorate::with('country:id,name');

        if ($this->filterCountry) {
            $q->where('id_country', $this->filterCountry);
        }
        if (filled($this->search)) {
            $s = "%{$this->search}%";
            $q->where(fn ($qq) => $qq->where('name->ar', 'like', $s)->orWhere('name->en', 'like', $s));
        }

        return $q->orderByDesc('id')->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.governorate.manage', [
            'rows' => $this->rows,
        ])->layout('layouts.master');
    }
}

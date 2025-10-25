<?php

namespace App\Http\Livewire\City;

use App\models\city;
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

    // فلاتر أعلى الجدول
    public $filterCountry = '';

    public $filterGovernorate = '';

    // فورم
    public $showModal = false;

    public $editingId = null;

    public $name_ar = '';

    public $name_en = '';

    public $id_country = '';

    public $id_governoratees = '';

    public $countries = [];

    public $governoratesOptions = [];

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
            'id_governoratees' => ['required', 'integer', Rule::exists((new Governorate)->getTable(), 'id')],
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterCountry()
    {
        $this->filterGovernorate = '';
        $this->resetPage();
    }

    public function updatedFilterGovernorate()
    {
        $this->resetPage();
    }

    public function updatedIdCountry()
    {
        $this->id_governoratees = '';
        $this->loadGovernorates();
    }

    protected function loadGovernorates()
    {
        $this->governoratesOptions = [];
        if ($this->id_country) {
            $this->governoratesOptions = Governorate::where('id_country', $this->id_country)
                ->select('id', 'name')->orderBy('name')->get();
        }
    }

    public function openCreate()
    {
        $this->reset(['editingId', 'name_ar', 'name_en', 'id_country', 'id_governoratees']);
        $this->governoratesOptions = [];
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $row = City::findOrFail($id);
        $this->editingId = $row->id;
        $this->name_ar = $row->getTranslation('name', 'ar');
        $this->name_en = $row->getTranslation('name', 'en');
        $this->id_country = $row->id_country;
        $this->loadGovernorates();
        $this->id_governoratees = $row->id_governoratees;
        $this->showModal = true;
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->editingId) {
            $row = City::findOrFail($this->editingId);
            $row->setTranslations('name', ['ar' => $data['name_ar'], 'en' => $data['name_en']]);
            $row->id_country = $data['id_country'];
            $row->id_governoratees = $data['id_governoratees'];
            $row->save();
        } else {
            City::create([
                'name' => ['ar' => $data['name_ar'], 'en' => $data['name_en']],
                'id_country' => $data['id_country'],
                'id_governoratees' => $data['id_governoratees'],
                'status' => 'active',
            ]);
        }

        $this->showModal = false;
        session()->flash('success', __('Saved successfully'));
    }

    public function delete($id)
    {
        City::findOrFail($id)->delete();
        session()->flash('success', __('Deleted successfully'));
    }

    public function getRowsProperty()
    {
        $q = City::with(['country:id,name', 'governoratees:id,name']);

        if ($this->filterCountry) {
            $q->where('id_country', $this->filterCountry);
        }
        if ($this->filterGovernorate) {
            $q->where('id_governoratees', $this->filterGovernorate);
        }
        if (filled($this->search)) {
            $s = "%{$this->search}%";
            $q->where(fn ($qq) => $qq->where('name->ar', 'like', $s)->orWhere('name->en', 'like', $s));
        }

        return $q->orderByDesc('id')->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.city.manage', [
            'rows' => $this->rows,
            'govsFilterOptions' => $this->filterCountry
                ? Governorate::where('id_country', $this->filterCountry)->select('id', 'name')->orderBy('name')->get()
                : collect(),
        ])->layout('layouts.master');
    }
}

<?php

namespace App\Http\Livewire\Area;

use App\models\Area;
use App\models\City;
use App\models\Country;
use App\models\Governorate;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Manage extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 10;

    public $filterCountry = '';

    public $filterGovernorate = '';

    public $filterCity = '';

    public $showModal = false;

    public $editingId = null;

    public $name_ar = '';

    public $name_en = '';

    public $id_country = '';

    public $id_governoratees = '';

    public $id_city = '';

    public $countries = [];

    public $governoratesOptions = [];

    public $citiesOptions = [];

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
            'id_city' => ['required', 'integer', Rule::exists((new City)->getTable(), 'id')],
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterCountry()
    {
        $this->filterGovernorate = '';
        $this->filterCity = '';
        $this->resetPage();
    }

    public function updatedFilterGovernorate()
    {
        $this->filterCity = '';
        $this->resetPage();
    }

    public function updatedFilterCity()
    {
        $this->resetPage();
    }

    public function updatedIdCountry()
    {
        $this->id_governoratees = '';
        $this->id_city = '';
        $this->loadGovernorates();
        $this->citiesOptions = [];
    }

    public function updatedIdGovernoratees()
    {
        $this->id_city = '';
        $this->loadCities();
    }

    protected function loadGovernorates()
    {
        $this->governoratesOptions = [];
        if ($this->id_country) {
            $this->governoratesOptions = Governorate::where('id_country', $this->id_country)
                ->select('id', 'name')->orderBy('name')->get();
        }
    }

    protected function loadCities()
    {
        $this->citiesOptions = [];
        if ($this->id_governoratees) {
            $this->citiesOptions = City::where('id_governoratees', $this->id_governoratees)
                ->select('id', 'name')->orderBy('name')->get();
        }
    }

    public function openCreate()
    {
        $this->reset(['editingId', 'name_ar', 'name_en', 'id_country', 'id_governoratees', 'id_city']);
        $this->governoratesOptions = [];
        $this->citiesOptions = [];
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $row = Area::findOrFail($id);
        $this->editingId = $row->id;
        $this->name_ar = $row->getTranslation('name', 'ar');
        $this->name_en = $row->getTranslation('name', 'en');
        $this->id_country = $row->id_country;
        $this->loadGovernorates();
        $this->id_governoratees = $row->id_governoratees;
        $this->loadCities();
        $this->id_city = $row->id_city;
        $this->showModal = true;
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->editingId) {
            $row = Area::findOrFail($this->editingId);
            $row->setTranslations('name', ['ar' => $data['name_ar'], 'en' => $data['name_en']]);
            $row->fill([
                'id_country' => $data['id_country'],
                'id_governoratees' => $data['id_governoratees'],
                'id_city' => $data['id_city'],
            ])->save();
        } else {
            Area::create([
                'name' => ['ar' => $data['name_ar'], 'en' => $data['name_en']],
                'id_country' => $data['id_country'],
                'id_governoratees' => $data['id_governoratees'],
                'id_city' => $data['id_city'],
                'status' => 'active',
            ]);
        }

        $this->showModal = false;
        session()->flash('success', __('Saved successfully'));
    }

    public function delete($id)
    {
        Area::findOrFail($id)->delete();
        session()->flash('success', __('Deleted successfully'));
    }

    public function getRowsProperty()
    {
        $q = Area::with(['country:id,name', 'governoratees:id,name', 'city:id,name']);

        if ($this->filterCountry) {
            $q->where('id_country', $this->filterCountry);
        }
        if ($this->filterGovernorate) {
            $q->where('id_governoratees', $this->filterGovernorate);
        }
        if ($this->filterCity) {
            $q->where('id_city', $this->filterCity);
        }

        if (filled($this->search)) {
            $s = "%{$this->search}%";
            $q->where(fn ($qq) => $qq->where('name->ar', 'like', $s)->orWhere('name->en', 'like', $s));
        }

        return $q->orderByDesc('id')->paginate($this->perPage);
    }

    public function render()
    {
        $govsFilter = $this->filterCountry
            ? Governorate::where('id_country', $this->filterCountry)->select('id', 'name')->orderBy('name')->get()
            : collect();

        $citiesFilter = $this->filterGovernorate
            ? City::where('id_governoratees', $this->filterGovernorate)->select('id', 'name')->orderBy('name')->get()
            : collect();

        return view('livewire.area.manage', [
            'rows' => $this->rows,
            'govsFilterOptions' => $govsFilter,
            'citiesFilterOptions' => $citiesFilter,
        ])->layout('layouts.master');
    }
}

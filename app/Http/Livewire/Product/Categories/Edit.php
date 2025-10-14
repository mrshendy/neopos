<?php

namespace App\Http\Livewire\Product\Categories;

use Livewire\Component;
use App\models\product\category;

class Edit extends Component
{
    public $category_id;
    public $name = ['ar' => '', 'en' => ''];
    public $description = ['ar' => '', 'en' => ''];
    public $status = 'active';

    protected $rules = [
        'name.ar' => 'required|string|max:255',
        'name.en' => 'required|string|max:255',
        'description.ar' => 'nullable|string|max:1000',
        'description.en' => 'nullable|string|max:1000',
        'status' => 'required|in:active,inactive',
    ];

    public function mount($id)
    {
        $cat = category::find($id);
        if (!$cat) {
            session()->flash('error', __('pos.no_data'));
            return redirect()->route('categories.index');
        }

        $this->category_id = $cat->id;
        $this->name = [
            'ar' => $cat->getTranslation('name', 'ar'),
            'en' => $cat->getTranslation('name', 'en'),
        ];
        $this->description = [
            'ar' => $cat->getTranslation('description', 'ar'),
            'en' => $cat->getTranslation('description', 'en'),
        ];
        $this->status = $cat->status;
    }

    public function save()
    {
        $this->validate();

        $cat = category::findOrFail($this->category_id);
        $cat->update([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        session()->flash('success', __('pos.msg_saved_ok'));
        return redirect()->route('categories.index');
    }

    public function render()
    {
        return view('livewire.product.categories.edit');
    }
}

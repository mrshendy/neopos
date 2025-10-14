<?php

namespace App\Http\Livewire\Product\Categories;

use Livewire\Component;
use App\models\product\category;

class Create extends Component
{
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

    protected $messages = [
        'name.ar.required' => 'اسم القسم بالعربية مطلوب.',
        'name.en.required' => 'اسم القسم بالإنجليزية مطلوب.',
    ];

    public function save()
    {
        $this->validate();

        category::create([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        session()->flash('success', __('pos.msg_saved_ok'));
        return redirect()->route('categories.index');
    }

    public function render()
    {
        return view('livewire.product.categories.create');
    }
}

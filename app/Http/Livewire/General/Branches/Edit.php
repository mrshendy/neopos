<?php

namespace App\Http\Livewire\General\Branches;

use Livewire\Component;
use App\Models\General\Branch as BranchModel; // ✅ صحّح الـnamespace والكابيتالايز

class Edit extends Component
{
    public BranchModel $branch;

    public $name = '';
    public $address = '';
    public $status = 1;

    protected $rules = [
        'name'    => 'required|string|min:2|max:190',
        'address' => 'nullable|string|max:255',
        'status'  => 'required|boolean',
    ];

    // ✅ أبسَط وأضمن: اعتمد فقط على Route Model Binding
    public function mount(BranchModel $branch): void
    {
        $this->branch  = $branch;
        $this->name    = $branch->name;
        $this->address = $branch->address;
        $this->status  = (int) $branch->status;
    }

    public function save()
    {
        $this->validate();

        $this->branch->update([
            'name'    => $this->name,
            'address' => $this->address,
            'status'  => (bool) $this->status,
        ]);

        session()->flash('success', __('branches.msg_updated'));
        return redirect()->route('branches.index');
    }

    public function render()
    {
        return view('livewire.general.branches.edit');
    }
}

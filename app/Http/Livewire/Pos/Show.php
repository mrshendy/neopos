<?php

namespace App\Http\Livewire\Pos;

use Livewire\Component;
use App\Models\pos\Pos;

class Show extends Component
{
    public $id;

    public function mount($id){ $this->id = (int)$id; }

    public function render()
    {
        $row = Pos::with(['lines.product','customer','warehouse'])->findOrFail($this->id);
        return view('livewire.pos.show', ['row'=>$row]);
    }
}

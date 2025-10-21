<?php

namespace App\Http\Livewire\Purchases;

use Livewire\Component;

// موديلاتك كما طلبت بالأحرف الصغيرة
use App\models\purchases\purchases as Purchase;

class Show extends Component
{
    public int $purchaseId;   // <— بدل $id
    public $row;
    public $lines;

    public function mount(int $purchaseId): void
    {
        $this->purchaseId = $purchaseId;

        $this->row = Purchase::with([
            'warehouse', 'supplier',
            'lines.product', 'lines.unit',
        ])->findOrFail($purchaseId);

        $this->lines = $this->row->lines;
    }

    public function render()
    {
        return view('livewire.purchases.show', [
            'row'   => $this->row,
            'lines' => $this->lines,
        ]);
    }
}

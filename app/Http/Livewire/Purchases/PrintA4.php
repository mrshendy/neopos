<?php

namespace App\Http\Livewire\Purchases;

use Livewire\Component;
use App\models\purchases\purchases as Purchase;

class PrintA4 extends Component
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
        // استخدم لياوت الطباعة إن كان عندك (اختياري)
        return view('livewire.purchases.print-a4', [
            'row'   => $this->row,
            'lines' => $this->lines,
        ])->layout('layouts.print'); // أو احذف السطر لو ما عندك لياوت للطباعة
    }
}

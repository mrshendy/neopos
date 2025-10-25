<?php

namespace App\Http\Livewire\Pos;

use Livewire\Component;
use App\models\pos\pos as Pos;

class Show extends Component
{
    public Pos $sale;

    public string  $paymentLabel   = '';
    public ?string $currencyName   = null;
    public ?string $currencySymbol = null;
    public ?string $treasuryName   = null;
    public ?int    $treasuryBranch = null;

    protected $listeners = ['deleteConfirmed' => 'deleteSale'];

    public function mount(int $id): void
    {
        // لا نطلب currency/financeSettings لأنها غير معرفة عندك
        $this->sale = Pos::with([
            'lines.product',
            'lines.unit',
            'warehouse',
            'customer',
            'user',
        ])->findOrFail($id);

        // طريقة الدفع: لو عندك عمود payment_method هنقرؤه، وإلا نعرض "نقدي"
        $this->paymentLabel = $this->mapPaymentMethod($this->sale->payment_method ?? 'cash');

        // العملة/الخزينة: طالما لا توجد علاقات أو أعمدة، نعرض قيمًا افتراضية
        $this->currencyName   = $this->sale->currency_name   ?? null;   // لو أضفت أعمدة نصية لاحقًا
        $this->currencySymbol = $this->sale->currency_symbol ?? null;

        $this->treasuryName   = $this->sale->treasury_name   ?? null;   // نفس الفكرة
        $this->treasuryBranch = $this->sale->treasury_branch_id ?? null;
    }

    public function render()
    {
        return view('livewire.pos.show', [
            'sale'            => $this->sale,
            'paymentLabel'    => $this->paymentLabel,
            'currencyName'    => $this->currencyName,
            'currencySymbol'  => $this->currencySymbol,
            'treasuryName'    => $this->treasuryName,
            'treasuryBranch'  => $this->treasuryBranch,
        ]);
    }

    public function deleteSale(int $id): void
    {
        if ($this->sale->id !== $id) { return; }

        $this->sale->lines()->delete();
        $this->sale->delete();

        session()->flash('success', __('تم حذف الفاتورة بنجاح'));
        redirect()->route('pos.index');
    }

    private function mapPaymentMethod(?string $method): string
    {
        $map = [
            'cash'     => __('pos.pm_cash'),
            'card'     => __('pos.pm_card'),
            'instapay' => __('pos.pm_instapay'),
            'wallet'   => __('pos.pm_wallet'),
        ];
        return $map[$method] ?? __('pos.pm_cash');
    }
}

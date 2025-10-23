<?php

namespace App\Http\Livewire\Pos;

use Livewire\Component;
use Illuminate\Support\Str;
use App\models\pos\pos as Pos;

class Show extends Component
{
    // لا تستخدم اسم $id عشان ما تتعارض مع Livewire\Component
    public int $posId;           // <-- بديل آمن
    public ?Pos $sale = null;

    public function mount(int $id): void
    {
        $this->posId = $id;
        $this->sale = Pos::with(['lines.product', 'lines.unit', 'warehouse', 'customer', 'user'])
            ->findOrFail($this->posId);
    }

    private function localize($raw): string
    {
        if (is_array($raw)) return (string)($raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw) ?: '')));
        if (is_string($raw)) {
            $t = trim($raw);
            if (Str::startsWith($t,'{') || Str::startsWith($t,'[')) {
                $arr = json_decode($t, true);
                if (is_array($arr)) return (string)($arr[app()->getLocale()] ?? ($arr['ar'] ?? $raw));
            }
            return $raw;
        }
        return (string)$raw;
    }

    public function render()
    {
        return view('livewire.pos.show', ['sale' => $this->sale]);
    }
}

<?php

namespace App\Http\Livewire\Inventory\Units;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\product\unit as Unit;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    /** فلاتر */
    public string $search = '';
    public string $status = '';   // '' | active | inactive
    public int $perPage = 10;

    protected $listeners = ['deleteConfirmed' => 'delete'];

    /* إعادة ضبط رقم الصفحة عند تغيير الفلاتر */
    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }
    public function updatingPerPage() { $this->resetPage(); }

    /** حذف آمن */
    public function delete(int $id): void
    {
        $u = Unit::find($id);

        if (! $u) {
            session()->flash('error', 'العنصر غير موجود.');
            return;
        }

        // منع حذف الكبرى إن كان لها صغرى
        if ($u->kind === 'major' && $u->minors()->count() > 0) {
            session()->flash('error', 'لا يمكن حذف وحدة كبرى تحتوي على وحدات صغرى.');
            return;
        }

        // منع الحذف إن كانت مربوطة بمنتجات
        if ($u->products()->count() > 0) {
            session()->flash('error', 'لا يمكن حذف وحدة مرتبطة بمنتجات.');
            return;
        }

        $u->delete(); // Soft Delete
        session()->flash('success', 'تم الحذف بنجاح.');
        $this->resetPage();
    }

    public function render()
    {
        $q = trim($this->search);

        $majors = Unit::query()
            ->where('kind', 'major')
            ->when($this->status !== '', fn($qq) =>
                $qq->where('status', $this->status)
            )
            ->when($q !== '', function ($qq) use ($q) {
                // نجمع شروط البحث داخل where() لتفادي تكسير الاستعلام
                $qq->where(function ($sub) use ($q) {
                    $like = "%{$q}%";
                    $sub->where('code', 'like', $like)
                        ->orWhere('name->ar', 'like', $like)
                        ->orWhere('name->en', 'like', $like);
                });
            })
            ->with(['minors' => function ($q2) use ($q) {
                $q2->when($this->status !== '', fn($qq) =>
                        $qq->where('status', $this->status)
                    )
                    ->when($q !== '', function ($qq) use ($q) {
                        $like = "%{$q}%";
                        $qq->where(function ($sub) use ($like) {
                            $sub->where('code', 'like', $like)
                                ->orWhere('name->ar', 'like', $like)
                                ->orWhere('name->en', 'like', $like);
                        });
                    })
                    ->orderByDesc('is_default_minor')
                    ->orderBy('code');
            }])
            ->orderBy('code')
            ->paginate($this->perPage);

        return view('livewire.inventory.units.index', compact('majors'));
    }
}

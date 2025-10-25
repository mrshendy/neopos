<?php

namespace App\Http\Livewire\product;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\product\product;
use App\models\product\category;
use App\models\unit\unit; 

class index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'delete'];

    // فلاتر
    public $search = '';
    public $status = '';
    public $category_id = '';
    public $unit_id = '';

    // اختيار متعدد للطباعة
    public $selected = [];                    // ids
    public $qty = [];                         // [product_id => qty]
    public $select_all_current_page = false;  // ✅ حل المشكلة

    protected $queryString = ['page','search','status','category_id','unit_id'];

    public function updating($field)
    {
        if (in_array($field, ['search','status','category_id','unit_id'], true)) {
            $this->resetPage();
            $this->selected = [];
            $this->select_all_current_page = false;
        }
    }

    public function delete($id)
    {
        $row = product::findOrFail($id);
        $row->delete();
        session()->flash('success', __('pos.deleted_success') ?: 'تم الحذف بنجاح');
    }

    public function toggleStatus($id)
    {
        $row = product::findOrFail($id);
        $row->status = $row->status === 'active' ? 'inactive' : 'active';
        $row->save();
        session()->flash('success', __('pos.status_changed') ?: 'تم تغيير الحالة');
    }

    protected function baseQuery()
    {
        return product::query()
            ->when($this->search, function($q){
                $s = "%{$this->search}%";
                $q->where(function($qq) use ($s){
                    $qq->where('sku','like',$s)
                       ->orWhere('barcode','like',$s)
                       ->orWhere('name->ar','like',$s)
                       ->orWhere('name->en','like',$s);
                });
            })
            ->when($this->status !== '', fn($q)=>$q->where('status',$this->status))
            ->when($this->category_id, fn($q)=>$q->where('category_id',$this->category_id))
            ->when($this->unit_id, fn($q)=>$q->where('unit_id',$this->unit_id))
            ->orderByDesc('id');
    }

    /** ✅ تحديد/إلغاء تحديد كل عناصر الصفحة الحالية */
    public function toggleSelectAllCurrentPage()
    {
        $this->select_all_current_page = !$this->select_all_current_page;

        $ids = $this->baseQuery()->paginate(10)->pluck('id')->map(fn($v)=>(int)$v)->all();

        if ($this->select_all_current_page) {
            $this->selected = array_values(array_unique(array_merge($this->selected, $ids)));
        } else {
            $this->selected = array_values(array_diff($this->selected, $ids));
        }
    }

    /** ✅ تحويل للطباعة عبر الراوت (كنترولر+بليد) */
    public function goPrintSelected()
    {
        $ids = array_map('intval', array_filter($this->selected));
        if (!$ids) {
            session()->flash('success', __('pos.no_data') ?: 'لا توجد بيانات محددة للطباعة');
            return;
        }

        $products = product::whereIn('id', $ids)
            ->get(['id','sku','barcode','name','image_path'])
            ->map(function($p){
                $q = (int)($this->qty[$p->id] ?? 1);
                if ($q < 1) $q = 1;
                return [
                    'id'      => (int)$p->id,
                    'sku'     => (string)$p->sku,
                    'barcode' => (string)($p->barcode ?? ''),
                    'label'   => (string)$p->getTranslation('name', app()->getLocale()),
                    'image'   => $p->image_path ? asset('attachments/'.ltrim($p->image_path,'/')) : null,
                    'qty'     => $q,
                ];
            })
            ->filter(fn($x)=> !empty($x['barcode']))
            ->values()
            ->toArray();

        if (!$products) {
            session()->flash('success', __('pos.no_data') ?: 'لا توجد أكواد باركود قابلة للطباعة');
            return;
        }

        $payload = base64_encode(json_encode(['items' => $products], JSON_UNESCAPED_UNICODE));
        return redirect()->route('product.barcodes', ['payload' => $payload]);
    }

    public function render()
    {
        $rows = $this->baseQuery()->paginate(10);

        // كمية افتراضية = 1 للسجلات الظاهرة
        foreach ($rows as $r) {
            if (!isset($this->qty[$r->id]) || (int)$this->qty[$r->id] < 1) {
                $this->qty[$r->id] = 1;
            }
        }

        return view('livewire.product.index', [
            'rows'       => $rows,
            'categories' => category::orderBy('name->'.app()->getLocale())->get(['id','name']),
            'units'      => unit::orderBy('name->'.app()->getLocale())->get(['id','name']),
        ]);
    }
}

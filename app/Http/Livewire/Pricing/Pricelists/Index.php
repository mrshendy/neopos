<?php

namespace App\Http\Livewire\Pricing\Pricelists;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\models\pricing\price_list;
use App\models\pricing\price_item;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'delete'];

    public $search = '';
    public $status = '';
    public $date_from = '';
    public $date_to = '';
    public $perPage = 10;

    protected $queryString = [
        'search'    => ['except' => ''],
        'status'    => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to'   => ['except' => ''],
        'perPage'   => ['except' => 10],
        'page'      => ['except' => 1],
    ];

    public function updating($name, $value)
    {
        if (in_array($name, ['search','status','date_from','date_to','perPage'])) {
            $this->resetPage();
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            price_item::where('price_list_id', $id)->delete();
            price_list::where('id', $id)->delete();
            DB::commit();
            session()->flash('success', __('pos.msg_deleted_ok') ?? 'تم الحذف بنجاح.');
            $this->resetPage();
        } catch (\Throwable $e) {
            DB::rollBack();
            session()->flash('error', 'تعذر الحذف: '.$e->getMessage());
        }
    }

    public function render()
    {
        $q = price_list::query();

        if ($this->search !== '') {
            $term = '%'.mb_strtolower($this->search).'%';
            $q->whereRaw('LOWER(`name`) LIKE ?', [$term]);
        }
        if ($this->status !== '') {
            $q->where('status', $this->status);
        }
        if ($this->date_from !== '') {
            $q->whereDate('valid_from', '>=', $this->date_from);
        }
        if ($this->date_to !== '') {
            $q->whereDate('valid_to', '<=', $this->date_to);
        }

        $lists = $q->orderBy('id','desc')->paginate((int)$this->perPage);

        $counts = price_item::select('price_list_id', DB::raw('COUNT(*) as cnt'))
            ->whereIn('price_list_id', $lists->pluck('id'))
            ->groupBy('price_list_id')
            ->pluck('cnt', 'price_list_id');

        return view('livewire.pricing.pricelists.index', compact('lists','counts'));
    }
}

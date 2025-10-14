<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
</div>
<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-alert-circle-outline me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card shadow-sm rounded-4 mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-lg-2">
                    <label class="form-label mb-1"><i class="mdi mdi-magnify"></i> {{ __('pos.search') }}</label>
                    <input type="text" class="form-control" wire:model.debounce.400ms="search" placeholder="{{ __('pos.ph_search_trx_no') }}">
                </div>
                <div class="col-lg-2">
                    <label class="form-label mb-1">{{ __('pos.type') }}</label>
                    <select class="form-select" wire:model="type">
                        <option value="">{{ __('pos.all') }}</option>
                        <option value="sales_issue">{{ __('pos.trx_sales_issue') }}</option>
                        <option value="sales_return">{{ __('pos.trx_sales_return') }}</option>
                        <option value="adjustment">{{ __('pos.trx_adjustment') }}</option>
                        <option value="transfer">{{ __('pos.trx_transfer') }}</option>
                        <option value="purchase_receive">{{ __('pos.trx_purchase_receive') }}</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label mb-1">{{ __('pos.status') }}</label>
                    <select class="form-select" wire:model="status">
                        <option value="">{{ __('pos.all') }}</option>
                        <option value="draft">{{ __('pos.draft') }}</option>
                        <option value="posted">{{ __('pos.posted') }}</option>
                        <option value="cancelled">{{ __('pos.cancelled') }}</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label mb-1">{{ __('pos.warehouse') }}</label>
                    <select class="form-select" wire:model="warehouse_id">
                        <option value="">{{ __('pos.all') }}</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}">{{ app()->getLocale()=='ar'?($w->name['ar']??''):($w->name['en']??'') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label mb-1">{{ __('pos.date_from') }}</label>
                    <input type="date" class="form-control" wire:model="date_from">
                </div>
                <div class="col-lg-2">
                    <label class="form-label mb-1">{{ __('pos.date_to') }}</label>
                    <input type="date" class="form-control" wire:model="date_to">
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('pos.trx_no') }}</th>
                            <th>{{ __('pos.type') }}</th>
                            <th>{{ __('pos.trx_date') }}</th>
                            <th>{{ __('pos.from') }}</th>
                            <th>{{ __('pos.to') }}</th>
                            <th>{{ __('pos.status') }}</th>
                            <th class="text-end">{{ __('pos.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trxs as $t)
                            <tr>
                                <td>{{ $t->id }}</td>
                                <td>{{ $t->trx_no }}</td>
                                <td>{{ __('pos.trx_'.$t->type) }}</td>
                                <td>{{ \Carbon\Carbon::parse($t->trx_date)->format('Y-m-d H:i') }}</td>
                                <td>{{ $t->from? (app()->getLocale()=='ar'?($t->from->name['ar']??''):($t->from->name['en']??'')) : '-' }}</td>
                                <td>{{ $t->to? (app()->getLocale()=='ar'?($t->to->name['ar']??''):($t->to->name['en']??'')) : '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $t->status=='posted'?'success':($t->status=='draft'?'secondary':'warning') }}">
                                        {{ __('pos.'.$t->status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-success rounded-pill shadow-sm" wire:click="post({{ $t->id }})" title="{{ __('pos.posted_success') }}">
                                        <i class="mdi mdi-check-all"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning rounded-pill shadow-sm" wire:click="cancel({{ $t->id }})" title="{{ __('pos.cancelled_success') }}">
                                        <i class="mdi mdi-cancel"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger rounded-pill shadow-sm" wire:click="confirmDelete({{ $t->id }})">
                                        <i class="mdi mdi-delete-outline"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted">{{ __('pos.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $trxs->onEachSide(1)->links() }}
        </div>
    </div>
{{-- ✅ SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'تحذير',
                text: '⚠️ هل أنت متأكد أنك تريد حذف هذا الإجراء لا يمكن التراجع عنه!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#0d6efd',
                confirmButtonText: 'نعم، احذفها',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('deleteConfirmed', id);
                    Swal.fire('تم الحذف!', '✅ تم الحذف  بنجاح.', 'success');
                }
            })
        }
    </script>


</div>

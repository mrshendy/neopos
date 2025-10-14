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

    <div class="card shadow-sm rounded-4 mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-lg-4">
                    <label class="form-label mb-1"><i class="mdi mdi-magnify"></i> {{ __('pos.search') }}</label>
                    <input type="text" class="form-control" wire:model.debounce.400ms="search" placeholder="{{ __('pos.ph_search_sku_name') }}">
                </div>
                <div class="col-lg-2">
                    <label class="form-label mb-1">{{ __('pos.status') }}</label>
                    <select class="form-select" wire:model="status">
                        <option value="">{{ __('pos.all') }}</option>
                        <option value="active">{{ __('pos.active') }}</option>
                        <option value="inactive">{{ __('pos.inactive') }}</option>
                    </select>
                </div>
                <div class="col-lg-6 text-end">
                    <a href="{{ route('inventory.warehouses.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-plus-circle-outline"></i> {{ __('pos.btn_new_warehouse') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('pos.warehouse') }}</th>
                            <th>Code</th>
                            <th>Branch</th>
                            <th>{{ __('pos.status') }}</th>
                            <th class="text-end">{{ __('pos.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($warehouses as $w)
                            <tr>
                                <td>{{ $w->id }}</td>
                                <td>{{ app()->getLocale()=='ar' ? ($w->name['ar'] ?? '') : ($w->name['en'] ?? '') }}</td>
                                <td>{{ $w->code }}</td>
                                <td>{{ $w->branch_id ?? '-' }}</td>
                                <td><span class="badge bg-{{ $w->status=='active'?'success':'secondary' }}">{{ __('pos.'.$w->status) }}</span></td>
                                <td class="text-end">
                                    <a href="{{ route('inventory.warehouses.edit', $w->id) }}" class="btn btn-sm btn-primary rounded-pill shadow-sm">
                                        <i class="mdi mdi-pencil-outline"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-warning rounded-pill shadow-sm" wire:click="toggleStatus({{ $w->id }})">
                                        <i class="mdi mdi-toggle-switch"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger rounded-pill shadow-sm" wire:click="confirmDelete({{ $w->id }})">
                                        <i class="mdi mdi-delete-outline"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted">{{ __('pos.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $warehouses->onEachSide(1)->links() }}
            </div>
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

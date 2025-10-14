<div class="page-wrap">
    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filters --}}
    <div class="card shadow-sm rounded-4 mb-3">
        <div class="card-body row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label"><i class="mdi mdi-magnify"></i> {{ __('pos.search') }}</label>
                <input class="form-control" type="text" wire:model.debounce.400ms="search"
                       placeholder="{{ __('pos.ph_search_sku_barcode_name') }}">
                <small class="text-muted">{{ __('pos.hint_search_products') }}</small>
            </div>
            <div class="col-md-3">
                <label class="form-label"><i class="mdi mdi-toggle-switch"></i> {{ __('pos.filter_status') }}</label>
                <select class="form-select" wire:model="status">
                    <option value="">{{ __('pos.all') }}</option>
                    <option value="active">{{ __('pos.status_active') }}</option>
                    <option value="inactive">{{ __('pos.status_inactive') }}</option>
                </select>
                <small class="text-muted">{{ __('pos.hint_status') }}</small>
            </div>
            <div class="col-md-3 text-end">
                <a href="{{ route('pricing.lists.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                    <i class="mdi mdi-plus"></i> {{ __('pos.btn_new_product') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('pos.name') }}</th>
                        <th>{{ __('pos.status') }}</th>
                        <th>{{ __('pos.tax_rate') }}</th>
                        <th> {{ __('pos.valid') }}</th>
                        <th class="text-end">{{ __('pos.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->getTranslation('name', app()->getLocale()) }}</td>
                            <td>
                                <span class="badge {{ $row->status=='active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $row->status=='active' ? __('pos.status_active') : __('pos.status_inactive') }}
                                </span>
                            </td>
                            <td>—</td>
                            <td>
                                {{ $row->valid_from ?? '—' }} → {{ $row->valid_to ?? '—' }}
                            </td>
                            <td class="text-end">
                                <button class="btn btn-outline-secondary btn-sm rounded-pill"
                                        wire:click="toggleStatus({{ $row->id }})">
                                    <i class="mdi mdi-toggle-switch"></i>
                                </button>
                                <a href="{{ route('pricing.lists.edit',$row->id) }}"
                                   class="btn btn-primary btn-sm rounded-pill">
                                    <i class="mdi mdi-pencil"></i>
                                </a>
                                <button onclick="confirmDelete({{ $row->id }})"
                                        class="btn btn-danger btn-sm rounded-pill">
                                    <i class="mdi mdi-delete-outline"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">{{ __('pos.no_data') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">
            {{ $rows->links() }}
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



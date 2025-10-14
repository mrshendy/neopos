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
            <div class="col-md-3">
                <label class="form-label"><i class="mdi mdi-magnify"></i> {{ __('pos.search') }}</label>
                <input type="text" class="form-control" wire:model.debounce.400ms="search" placeholder="{{ __('pos.ph_search_sku_barcode_name') }}">
                <small class="text-muted">{{ __('pos.hint_search_products') }}</small>
            </div>
            <div class="col-md-3">
                <label class="form-label"><i class="mdi mdi-shape-outline"></i> {{ __('pos.filter_category') }}</label>
                <select class="form-select" wire:model="category_id">
                    <option value="">{{ __('pos.all') }}</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->getTranslation('name',app()->getLocale()) }}</option>
                    @endforeach
                </select>
                <small class="text-muted">{{ __('pos.hint_category') }}</small>
            </div>
            <div class="col-md-2">
                <label class="form-label"><i class="mdi mdi-weight-kilogram"></i> {{ __('pos.filter_unit') }}</label>
                <select class="form-select" wire:model="unit_id">
                    <option value="">{{ __('pos.all') }}</option>
                    @foreach($units as $u)
                        <option value="{{ $u->id }}">{{ $u->getTranslation('name',app()->getLocale()) }}</option>
                    @endforeach
                </select>
                <small class="text-muted">{{ __('pos.hint_unit') }}</small>
            </div>
            <div class="col-md-2">
                <label class="form-label"><i class="mdi mdi-toggle-switch"></i> {{ __('pos.filter_status') }}</label>
                <select class="form-select" wire:model="status">
                    <option value="">{{ __('pos.all') }}</option>
                    <option value="active">{{ __('pos.status_active') }}</option>
                    <option value="inactive">{{ __('pos.status_inactive') }}</option>
                </select>
                <small class="text-muted">{{ __('pos.hint_status') }}</small>
            </div>
            <div class="col-md-2 text-end">
                <a href="{{ route('products.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
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
                        <th>{{ __('pos.sku') }}</th>
                        <th>{{ __('pos.barcode') }}</th>
                        <th>{{ __('pos.name') }}</th>
                        <th>{{ __('pos.unit') }}</th>
                        <th>{{ __('pos.category') }}</th>
                        <th>{{ __('pos.status') }}</th>
                        <th class="text-end">{{ __('pos.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->sku }}</td>
                            <td>{{ $row->barcode }}</td>
                            <td>{{ $row->getTranslation('name', app()->getLocale()) }}</td>
                            <td>{{ optional($row->unit)->getTranslation('name', app()->getLocale()) }}</td>
                            <td>{{ optional($row->category)->getTranslation('name', app()->getLocale()) }}</td>
                            <td>
                                <span class="badge {{ $row->status=='active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $row->status=='active' ? __('pos.status_active') : __('pos.status_inactive') }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-outline-secondary btn-sm rounded-pill"
                                        wire:click="toggleStatus({{ $row->id }})">
                                    <i class="mdi mdi-toggle-switch"></i>
                                </button>
                                <a href="{{ route('products.edit',$row->id) }}" class="btn btn-primary btn-sm rounded-pill">
                                    <i class="mdi mdi-pencil"></i>
                                </a>
                                <button onclick="confirmDelete({{ $row->id }})" class="btn btn-danger btn-sm rounded-pill">
                                    <i class="mdi mdi-delete-outline"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">{{ __('pos.no_data') }}</td></tr>
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
<script>
function confirmDelete(id) {
    Swal.fire({
        title: '{{ __("pos.alert_title") }}',
        text: '{{ __("pos.alert_text") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#0d6efd',
        confirmButtonText: '{{ __("pos.alert_confirm") }}',
        cancelButtonText: '{{ __("pos.alert_cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.emit('deleteConfirmed', id);
            Swal.fire('{{ __("pos.deleted") }}', '{{ __("pos.msg_deleted_ok") }}', 'success');
        }
    })
}
</script>

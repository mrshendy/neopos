<div class="page-wrap">

    {{-- ✅ Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 🏷️ Page Header (عنوان مترجم) --}}
    <div class="card border-0 rounded-4 shadow-sm mb-3 stylish-card">
        <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h4 class="mb-1 fw-bold">
                    <i class="mdi mdi-shape-outline me-2"></i> {{ __('pos.nav_categories_sub') }}
                </h4>
                <div class="text-muted small">
                    {{ __('pos.categories_index_sub') }}
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('product.manage') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                    <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') }}
                </a>
                <a href="{{ route('categories.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                    <i class="mdi mdi-plus"></i> {{ __('pos.category_create_title') }}
                </a>
            </div>
        </div>
    </div>

    {{-- 🔹 Filters --}}
    <div class="card shadow-sm rounded-4 mb-2 stylish-card">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label mb-1">
                        <i class="mdi mdi-magnify"></i> {{ __('pos.search') }}
                    </label>
                    <input type="text" class="form-control" wire:model.debounce.400ms="search"
                           placeholder="{{ __('pos.ph_search_category') }}">
                    <small class="text-muted">{{ __('pos.hint_search_category') }}</small>
                </div>

                <div class="col-md-3">
                    <label class="form-label mb-1">
                        <i class="mdi mdi-toggle-switch"></i> {{ __('pos.filter_status') }}
                    </label>
                    <select class="form-select" wire:model="status">
                        <option value="">{{ __('pos.all') }}</option>
                        <option value="active">{{ __('pos.status_active') }}</option>
                        <option value="inactive">{{ __('pos.status_inactive') }}</option>
                    </select>
                    <small class="text-muted">{{ __('pos.hint_status') }}</small>
                </div>

                <div class="col-md-4 text-end">
                    <button class="btn btn-light rounded-pill px-3 shadow-sm"
                            wire:click="$set('search','');$set('status','')">
                        <i class="mdi mdi-filter-remove-outline"></i> {{ __('pos.clear_filters') }}
                    </button>
                </div>
            </div>

            {{-- Filter chips summary --}}
            <div class="d-flex flex-wrap align-items-center gap-2 mt-3 small text-muted">
                <span><i class="mdi mdi-dots-grid me-1"></i>{{ __('pos.search') }}:</span>
                <span class="badge bg-light text-dark">{{ $search ?: '—' }}</span>

                <span class="ms-2">{{ __('pos.filter_status') }}:</span>
                <span class="badge bg-light text-dark">
                    {{ $status ? __($status=='active' ? 'pos.status_active' : 'pos.status_inactive') : __('pos.all') }}
                </span>

                <span class="ms-auto">
                    <span class="badge bg-primary-subtle text-primary rounded-pill">
                        <i class="mdi mdi-counter me-1"></i>{{ $rows->total() }}
                    </span>
                </span>
            </div>
        </div>
    </div>

    {{-- 🧾 Table --}}
    <div class="card shadow-sm rounded-4 stylish-card">
        <div class="table-responsive">
            <table class="table table-hover table-borderless align-middle mb-0 pretty-table">
                <thead class="table-light">
                    <tr>
                        <th class="w-1">#</th>
                        <th>{{ __('pos.name') }}</th>
                        <th>{{ __('pos.description') }}</th>
                        <th class="w-1">{{ __('pos.status') }}</th>
                        <th class="text-end w-1">{{ __('pos.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td class="text-muted">{{ $row->id }}</td>

                            <td class="text-truncate" title="{{ $row->getTranslation('name', app()->getLocale()) }}">
                                {{ $row->getTranslation('name', app()->getLocale()) }}
                            </td>

                            <td class="text-truncate" style="max-width: 420px;"
                                title="{{ $row->hasTranslation('description') ? $row->getTranslation('description', app()->getLocale()) : ($row->description ?? '-') }}">
                                {{ $row->hasTranslation('description') ? $row->getTranslation('description', app()->getLocale()) : ($row->description ?? '-') }}
                            </td>

                            <td>
                                <span class="badge rounded-pill px-3 {{ $row->status=='active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                    {{ $row->status=='active' ? __('pos.status_active') : __('pos.status_inactive') }}
                                </span>
                            </td>

                            <td class="text-end">
                                <div class="btn-group">
                                    <button class="btn btn-outline-secondary btn-sm rounded-pill m-1"
                                            wire:click="toggleStatus({{ $row->id }})" data-bs-toggle="tooltip"
                                            title="{{ __('pos.toggle_status') }}">
                                        <i class="mdi mdi-toggle-switch"></i>
                                    </button>
                                    <a href="{{ route('categories.edit', $row->id) }}"
                                       class="btn btn-primary btn-sm rounded-pill m-1" data-bs-toggle="tooltip"
                                       title="{{ __('pos.btn_edit') }}">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <button onclick="confirmDelete({{ $row->id }})"
                                            class="btn btn-danger btn-sm rounded-pill m-1" data-bs-toggle="tooltip"
                                            title="{{ __('pos.btn_delete') }}">
                                        <i class="mdi mdi-delete-outline"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="py-5 text-center text-muted">
                                    <i class="mdi mdi-shape-outline fs-1 d-block mb-2"></i>
                                    {{ __('pos.no_data') }}
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination footer --}}
        <div class="card-body d-flex justify-content-between align-items-center">
            <div class="small text-muted">
                <i class="mdi mdi-information-outline"></i>
                {{ $rows->firstItem() }}–{{ $rows->lastItem() }} / {{ $rows->total() }}
            </div>
            <div>
                {{ $rows->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
</div>

{{-- ✅ SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    // تفعيل الـ Tooltips إن وُجد Bootstrap
    document.addEventListener('DOMContentLoaded', () => {
        if (window.bootstrap) {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
        }
    });
</script>

{{-- Styles --}}
<style>
    .stylish-card{ border:1px solid rgba(0,0,0,.06); }
    .pretty-table thead th{
        position: sticky; top: 0; z-index: 1;
        background: var(--bs-light, #f8f9fa);
        border-bottom: 1px solid rgba(0,0,0,.06);
    }
    .table-hover tbody tr:hover{ background: rgba(13,110,253,.03); }
    .w-1{ width: 1%; white-space: nowrap; }
</style>

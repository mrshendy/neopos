<div class="page-wrap container-fluid px-3">
    {{-- Alerts --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-2 small">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
        .preview-chip{
            display:inline-flex;align-items:center;gap:.35rem;
            background:#f8f9fa;border:1px solid rgba(0,0,0,.06);
            border-radius:999px;padding:.25rem .6rem;font-size:.8rem;color:#6c757d
        }
    </style>
{{-- ===== Page Title ===== --}}
<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">
        <i class="mdi mdi-office-building-outline me-2"></i>
        {{ __('branches.index_title') }}
    </h4>

</div>
    {{-- Toolbar --}}
    <div class="card rounded-4 shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-lg-3">
                    <label class="form-label mb-1">
                        <i class="mdi mdi-magnify"></i> {{ __('branches.search') }}
                    </label>
                    <input type="text" class="form-control" wire:model.debounce.400ms="search"
                           placeholder="{{ __('branches.ph_search') }}">
                    <small class="text-muted">{{ __('branches.hint_search') }}</small>
                </div>

                <div class="col-lg-3">
                    <label class="form-label mb-1">{{ __('branches.filter_status') }}</label>
                    <select class="form-select" wire:model="status">
                        <option value="">{{ __('branches.all') }}</option>
                        <option value="1">{{ __('branches.status_active') }}</option>
                        <option value="0">{{ __('branches.status_inactive') }}</option>
                    </select>
                </div>

                <div class="col-lg-2">
                    <label class="form-label mb-1">{{ __('branches.per_page') }}</label>
                    <select class="form-select" wire:model="perPage">
                        <option>10</option><option>25</option><option>50</option>
                    </select>
                </div>

                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('branches.create') }}"
                       class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-plus"></i> {{ __('branches.btn_new_branch') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card rounded-4 shadow-sm stylish-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:80px">#</th>
                        <th>{{ __('branches.col_name') }}</th>
                        <th>{{ __('branches.col_address') }}</th>
                        <th style="width:110px">{{ __('branches.col_status') }}</th>
                        <th style="width:170px" class="text-end">{{ __('branches.col_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $r)
                        <tr>
                            <td>{{ $r->id }}</td>
                            <td>{{ $r->name }}</td>
                            <td>{{ $r->address }}</td>
                            <td>
                                @if($r->status)
                                    <span class="badge bg-success">{{ __('branches.status_active') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('branches.status_inactive') }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('branches.edit', $r->id) }}"
                                   class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    {{ __('branches.btn_edit') }}
                                </a>
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                        onclick="confirmDelete({{ $r->id }})">
                                    {{ __('branches.btn_delete') }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                {{ __('branches.no_data') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $rows->links() }}
        </div>
    </div>

    {{-- ✅ SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: @json(__('branches.sa_title')),
                text: @json(__('branches.sa_text')),
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#0d6efd',
                confirmButtonText: @json(__('branches.sa_confirm')),
                cancelButtonText: @json(__('branches.sa_cancel'))
            }).then((result) => {
                if (result.isConfirmed) {
                    // ملاحظة: لازم الليسنر في الكومبوننت يكون اسمه 'deleteConfirmed'
                    Livewire.emit('deleteConfirmed', id);
                    Swal.fire(@json(__('branches.sa_done')), @json(__('branches.sa_done_text')), 'success');
                }
            });
        }
    </script>
</div>

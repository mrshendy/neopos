<div class="container-fluid">

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

    <style>
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
        .badge-status{font-weight:700}
        .badge-status.active{background:#f0fdf4;color:#166534;border:1px solid rgba(22,101,52,.15)}
        .badge-status.inactive{background:#fef2f2;color:#991b1b;border:1px solid rgba(153,27,27,.15)}
        .toolbar{display:flex;gap:.5rem;align-items:center}
        .toolbar .btn{border-radius:9999px}
        .metric{border-radius:16px;border:1px solid rgba(0,0,0,.06);background:#fff;box-shadow:0 6px 18px rgba(13,110,253,.06)}
        .metric .v{font-size:1.25rem;font-weight:800}
        .metric .k{color:#6b7280}
        .table thead th{background:#f8f9fc;position:sticky;top:0;z-index:1}
    </style>

    <div class="card shadow-sm rounded-4 stylish-card mb-4">
        <div class="card-header bg-light d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0 fw-bold"><i class="mdi mdi-account-multiple-outline me-1"></i> {{ __('pos.customers_index_title') }}</h5>
                <div class="text-muted small">{{ __('pos.customers_index_subtitle') ?? '' }}</div>
            </div>
            <div class="toolbar">
                <a href="{{ route('customers.create') }}" class="btn btn-primary rounded-pill px-3 shadow-sm">
                    <i class="mdi mdi-plus-circle-outline me-1"></i> {{ __('pos.customers_new') }}
                </a>
                <div class="d-flex align-items-center gap-2">
                    <div class="text-muted small">{{ __('pos.per_page') }}</div>
                    <select class="form-select form-select-sm" style="width:auto" wire:model="perPage">
                        <option>10</option><option>20</option><option>30</option><option>50</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card-body">
            {{-- Top metrics --}}
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="metric p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="k">{{ __('pos.records_showing') ?? 'Showing' }}</div>
                            <div class="v">{{ $customers->count() }}</div>
                        </div>
                        <i class="mdi mdi-eye-outline fs-2 text-primary"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="k">{{ __('pos.records_total') ?? 'Total' }}</div>
                            <div class="v">{{ $customers->total() }}</div>
                        </div>
                        <i class="mdi mdi-database-outline fs-2 text-primary"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="k">{{ __('pos.updated_at') }}</div>
                            <div class="v">{{ now()->format('Y-m-d H:i') }}</div>
                        </div>
                        <i class="mdi mdi-update fs-2 text-primary"></i>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="row g-2 mb-3">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">{{ __('pos.search') }}</label>
                    <input type="text" class="form-control" placeholder="{{ __('pos.customers_search_ph') }}" wire:model.debounce.400ms="search">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">{{ __('pos.status') }}</label>
                    <select class="form-select" wire:model="status">
                        <option value="">{{ __('pos.status_all') ?? __('pos.all') }}</option>
                        <option value="active">{{ __('pos.status_active') }}</option>
                        <option value="inactive">{{ __('pos.status_inactive') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">{{ __('pos.date_from') }}</label>
                    <input type="date" class="form-control" wire:model="date_from">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">{{ __('pos.date_to') }}</label>
                    <input type="date" class="form-control" wire:model="date_to">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-primary w-100" wire:click="$refresh">
                        <i class="mdi mdi-filter-outline"></i> {{ __('pos.refresh') }}
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th style="width:120px">{{ __('pos.code') }}</th>
                            <th>{{ __('pos.name') ?? __('pos.name_ar') }}</th>
                            <th style="width:150px">{{ __('pos.phone') }}</th>
                            <th style="width:220px">{{ __('pos.email') }}</th>
                            <th style="width:120px">{{ __('pos.country') }}</th>
                            <th class="text-center" style="width:110px">{{ __('pos.status') }}</th>
                            <th style="width:170px">{{ __('pos.created_at') }}</th>
                            <th style="width:220px">{{ __('pos.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $r)
                            @php
                                $loc = app()->getLocale();
                                $nm = is_array($r->name) ? ($r->name[$loc] ?? ($r->name['ar'] ?? $r->name['en'] ?? '')) : $r->name;
                            @endphp
                            <tr>
                                <td class="fw-semibold">{{ $r->code }}</td>
                                <td>{{ $nm }}</td>
                                <td>{{ $r->phone ?: '—' }}</td>
                                <td>{{ $r->email ?: '—' }}</td>
                                <td>{{ $r->country ?: '—' }}</td>
                                <td class="text-center">
                                    <span class="badge badge-status {{ $r->status }}">{{ __('pos.status_'.$r->status) }}</span>
                                </td>
                                <td>{{ $r->created_at?->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('customers.show', $r->id) }}" class="btn btn-sm btn-outline-info" title="{{ __('pos.show') }}">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </a>
                                        <a href="{{ route('customers.edit', $r->id) }}" class="btn btn-sm btn-outline-primary" title="{{ __('pos.edit') }}">
                                            <i class="mdi mdi-pencil-outline"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="toggle({{ $r->id }})" title="{{ __('pos.change_status') }}">
                                            <i class="mdi mdi-swap-horizontal"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $r->id }})" title="{{ __('pos.delete') }}">
                                            <i class="mdi mdi-trash-can-outline"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">{{ __('pos.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $customers->links() }}
            </div>
        </div>
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function confirmDelete(id){
        Swal.fire({
            title: '{{ __("pos.confirm_delete_title") }}',
            text: '{{ __("pos.confirm_delete_text") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#0d6efd',
            confirmButtonText: '{{ __("pos.confirm") }}',
            cancelButtonText: '{{ __("pos.cancel") }}'
        }).then((r)=>{ if(r.isConfirmed){ Livewire.emit('deleteConfirmed', id); Swal.fire('{{ __("pos.deleted") }}','{{ __("pos.deleted_ok") }}','success'); }});
    }
    </script>
</div>

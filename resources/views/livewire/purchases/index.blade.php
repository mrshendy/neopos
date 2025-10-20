<div>
    {{-- Alerts --}}
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
        .table thead th{background:#f8f9fc; white-space:nowrap}
        .table td,.table th{vertical-align:middle}
        .badge-status{font-weight:600}
        .badge-status.draft{background:#eef2ff;color:#3730a3;border:1px solid rgba(55,48,163,.15)}
        .badge-status.approved{background:#ecfdf5;color:#065f46;border:1px solid rgba(6,95,70,.15)}
        .badge-status.posted{background:#eff6ff;color:#1e40af;border:1px solid rgba(30,64,175,.15)}
        .badge-status.cancelled{background:#fef2f2;color:#991b1b;border:1px solid rgba(153,27,27,.15)}
    </style>

    <div class="card shadow-sm rounded-4 stylish-card mb-4">
        <div class="card-header bg-light fw-bold d-flex align-items-center justify-content-between">
            <div><i class="mdi mdi-receipt-text-outline me-1"></i> {{ __('pos.purchases_list') }}</div>
            <div class="d-flex align-items-center gap-2">
                <div class="text-muted small">{{ __('pos.per_page') }}</div>
                <select class="form-select form-select-sm" style="width:auto" wire:model="per_page">
                    <option>10</option><option>20</option><option>30</option><option>50</option>
                </select>
            </div>
        </div>

        <div class="card-body">
            {{-- Filters --}}
            <div class="row g-2 mb-3">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">{{ __('pos.search') }}</label>
                    <input type="text" class="form-control" placeholder="{{ __('pos.search_ph') }}" wire:model.debounce.500ms="search">
                </div>

                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">{{ __('pos.status') }}</label>
                    <select class="form-select" wire:model="status">
                        <option value="">{{ __('pos.all') }}</option>
                        <option value="draft">{{ __('pos.status_draft') }}</option>
                        <option value="approved">{{ __('pos.status_approved') }}</option>
                        <option value="posted">{{ __('pos.status_posted') }}</option>
                        <option value="cancelled">{{ __('pos.status_cancelled') }}</option>
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

                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">{{ __('pos.warehouse') }}</label>
                    <select class="form-select" wire:model="warehouse_id">
                        <option value="">{{ __('pos.all') }}</option>
                        @foreach($warehouses as $w)
                            @php
                                $name = $w->name;
                                if (is_string($name) && strlen($name) && $name[0]==='{') {
                                    $arr = json_decode($name,true) ?: [];
                                    $name = $arr[app()->getLocale()] ?? $arr['ar'] ?? $arr['en'] ?? $w->name;
                                }
                            @endphp
                            <option value="{{ $w->id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">{{ __('pos.supplier') }}</label>
                    <select class="form-select" wire:model="supplier_id">
                        <option value="">{{ __('pos.all') }}</option>
                        @foreach($suppliers as $s)
                            @php
                                $name = $s->name;
                                if (is_string($name) && strlen($name) && $name[0]==='{') {
                                    $arr = json_decode($name,true) ?: [];
                                    $name = $arr[app()->getLocale()] ?? $arr['ar'] ?? $arr['en'] ?? $s->name;
                                }
                            @endphp
                            <option value="{{ $s->id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th style="width:120px">{{ __('pos.purchase_no') }}</th>
                            <th style="width:120px">{{ __('pos.purchase_date') }}</th>
                            <th style="width:120px">{{ __('pos.supply_date') }}</th>
                            <th>{{ __('pos.warehouse') }}</th>
                            <th>{{ __('pos.supplier') }}</th>
                            <th class="text-center" style="width:120px">{{ __('pos.status') }}</th>
                            <th class="text-end" style="width:140px">{{ __('pos.grand_total') }}</th>
                            <th style="width:150px">{{ __('pos.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $row)
                            <tr>
                                <td class="fw-semibold">{{ $row->purchase_no }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($row->purchase_date)->format('Y-m-d') }}</td>
                                <td>{{ $row->supply_date ? \Illuminate\Support\Carbon::parse($row->supply_date)->format('Y-m-d') : '—' }}</td>
                                <td>{{ $row->wh_name ?? '—' }}</td>
                                <td>{{ $row->sup_name ?? '—' }}</td>
                                <td class="text-center">
                                    <span class="badge badge-status {{ $row->status }}">
                                        {{ __("pos.status_{$row->status}") }}
                                    </span>
                                </td>
                                <td class="text-end">{{ number_format((float)$row->grand_total, 4) }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('purchases.index') }}#"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </a>
                                        <a href="{{ route('purchases.index') }}#"
                                           class="btn btn-sm btn-outline-secondary">
                                            <i class="mdi mdi-pencil-outline"></i>
                                        </a>

                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="confirmDelete({{ $row->id }})">
                                            <i class="mdi mdi-trash-can-outline"></i>
                                        </button>

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="mdi mdi-flag-variant-outline"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#" wire:click.prevent="changeStatus({{ $row->id }}, 'draft')">{{ __('pos.status_draft') }}</a></li>
                                                <li><a class="dropdown-item" href="#" wire:click.prevent="changeStatus({{ $row->id }}, 'approved')">{{ __('pos.status_approved') }}</a></li>
                                                <li><a class="dropdown-item" href="#" wire:click.prevent="changeStatus({{ $row->id }}, 'posted')">{{ __('pos.status_posted') }}</a></li>
                                                <li><a class="dropdown-item text-danger" href="#" wire:click.prevent="changeStatus({{ $row->id }}, 'cancelled')">{{ __('pos.status_cancelled') }}</a></li>
                                            </ul>
                                        </div>
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
                {{ $purchases->links() }}
            </div>
        </div>
    </div>

    {{-- ✅ SweetAlert2 تأكيد الحذف --}}
    <script>
    function confirmDelete(id) {
        Swal.fire({
            title: '{{ __("pos.delete_title") }}',
            text: '{{ __("pos.delete_text") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#0d6efd',
            confirmButtonText: '{{ __("pos.delete_yes") }}',
            cancelButtonText: '{{ __("pos.delete_cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('deleteConfirmed', id);
                Swal.fire('{{ __("pos.deleted") }}', '{{ __("pos.deleted_ok") }}', 'success');
            }
        })
    }
    </script>
</div>

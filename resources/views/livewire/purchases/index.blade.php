<div class="container-fluid">

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
        .table thead th{background:#f8f9fc;white-space:nowrap;position:sticky;top:0;z-index:1}
        .table td,.table th{vertical-align:middle}
        .btn-soft{border:1px solid rgba(0,0,0,.08);background:#f9fafb}
        .btn-soft:hover{background:#f3f4f6}
        .btn-soft-primary{color:#0d6efd;border-color:rgba(13,110,253,.15)}
        .btn-soft-secondary{color:#334155;border-color:rgba(51,65,85,.15)}
        .btn-soft-info{color:#0ea5e9;border-color:rgba(14,165,233,.18)}
        .btn-soft-success{color:#16a34a;border-color:rgba(22,163,74,.18)}
        .btn-soft-danger{color:#dc2626;border-color:rgba(220,38,38,.18)}
        .badge-status{font-weight:600;border:1px solid transparent}
        .badge-status.draft{background:#eef2ff;color:#3730a3;border-color:rgba(55,48,163,.15)}
        .badge-status.approved{background:#f0fdf4;color:#166534;border-color:rgba(22,101,52,.15)}
        .badge-status.posted{background:#eff6ff;color:#1e40af;border-color:rgba(30,64,175,.15)}
        .badge-status.cancelled{background:#fef2f2;color:#991b1b;border-color:rgba(153,27,27,.15)}
        .toolbar .form-select,.toolbar .form-control{border-radius:9999px}
        .toolbar .btn{border-radius:9999px}
    </style>

    <div class="card shadow-sm rounded-4 stylish-card">
        <div class="card-header bg-light d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0 fw-bold">
                    <i class="mdi mdi-receipt-text-outline me-1"></i> {{ __('pos.purchases_index_title') }}
                </h5>
                <div class="text-muted small">{{ __('pos.purchases_index_subtitle') }}</div>
            </div>

            <div class="toolbar d-flex align-items-center gap-2">
                <a href="{{ route('purchases.create') }}"
                   class="btn btn-primary rounded-pill px-3 shadow-sm">
                    <i class="mdi mdi-plus-circle-outline me-1"></i> {{ __('pos.purchases_new') }}
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
            {{-- Filters --}}
            <div class="row g-2 mb-3">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">{{ __('pos.search') }}</label>
                    <input type="text" class="form-control" placeholder="{{ __('pos.purchases_search_ph') }}"
                           wire:model.debounce.400ms="search">
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
                                $wname = $w->name;
                                if (is_string($wname) && str_starts_with(trim($wname), '{')) {
                                    $a=json_decode($wname,true)?:[];
                                    $wname=$a[app()->getLocale()]??$a['ar']??$w->name;
                                }
                            @endphp
                            <option value="{{ $w->id }}">{{ $wname }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">{{ __('pos.supplier') }}</label>
                    <select class="form-select" wire:model="supplier_id">
                        <option value="">{{ __('pos.all') }}</option>
                        @foreach($suppliers as $s)
                            @php
                                $sname = $s->name;
                                if (is_string($sname) && str_starts_with(trim($sname), '{')) {
                                    $a=json_decode($sname,true)?:[];
                                    $sname=$a[app()->getLocale()]??$a['ar']??$s->name;
                                }
                            @endphp
                            <option value="{{ $s->id }}">{{ $sname }}</option>
                        @endforeach
                    </select>
                </div>


            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th style="width:140px">{{ __('pos.purchase_no') }}</th>
                            <th style="width:120px">{{ __('pos.purchase_date') }}</th>
                            <th style="width:120px">{{ __('pos.delivery_date') }}</th>
                            <th>{{ __('pos.supplier') }}</th>
                            <th>{{ __('pos.warehouse') }}</th>
                            <th class="text-center" style="width:110px">{{ __('pos.items_count') }}</th>
                            <th class="text-end" style="width:150px">{{ __('pos.grand_total') }}</th>
                            <th class="text-center" style="width:120px">{{ __('pos.status') }}</th>
                            <th style="width:260px">{{ __('pos.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $r)
                            @php
                                $wn = $r->warehouse->name ?? '—';
                                if (is_string($wn) && str_starts_with(trim($wn), '{')) {
                                    $a=json_decode($wn,true)?:[]; $wn = $a[app()->getLocale()]??$a['ar']??$wn;
                                }
                                $sn = $r->supplier->name ?? '—';
                                if (is_string($sn) && str_starts_with(trim($sn), '{')) {
                                    $a=json_decode($sn,true)?:[]; $sn = $a[app()->getLocale()]??$a['ar']??$sn;
                                }
                                $count = $r->lines_count ?? ($r->items_count ?? (isset($r->lines) ? count($r->lines) : 0));
                                $delivery = $r->supply_date ?? $r->delivery_date;
                                $status = $r->status ?? 'draft';
                                $statusKey = 'pos.status_'.$status;
                            @endphp
                            <tr>
                                <td class="fw-semibold">{{ $r->purchase_no ?? ('#'.$r->id) }}</td>
                                <td>{{ $r->purchase_date ? \Illuminate\Support\Carbon::parse($r->purchase_date)->format('Y-m-d') : '—' }}</td>
                                <td>{{ $delivery ? \Illuminate\Support\Carbon::parse($delivery)->format('Y-m-d') : '—' }}</td>
                                <td>{{ $sn }}</td>
                                <td>{{ $wn }}</td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">{{ $count }}</span>
                                </td>
                                <td class="text-end fw-semibold">
                                    {{ number_format((float)($r->grand_total ?? 0), 2) }}
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-status {{ $status }}">
                                        {{ \Illuminate\Support\Facades\Lang::has($statusKey) ? __($statusKey) : strtoupper($status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <a href="{{ route('purchases.show', $r->id) }}"
                                           class="btn btn-sm btn-soft btn-soft-info"
                                           title="{{ __('pos.view') }}">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </a>

                                        <a href="{{ route('purchases.print', $r->id) }}" target="_blank"
                                           class="btn btn-sm btn-soft btn-soft-secondary"
                                           title="{{ __('pos.print') }}">
                                            <i class="mdi mdi-printer"></i>
                                        </a>

                                        <a href="{{ route('purchases.edit', $r->id) }}"
                                           class="btn btn-sm btn-soft btn-soft-primary"
                                           title="{{ __('pos.edit') }}">
                                            <i class="mdi mdi-pencil-outline"></i>
                                        </a>

                                        {{-- Change status --}}
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-soft btn-soft-success dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="mdi mdi-swap-horizontal"></i> {{ __('pos.change_status') }}
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <button class="dropdown-item"
                                                            @if($status==='approved') disabled @endif
                                                            onclick="confirmStatus({{ $r->id }}, 'approved')">
                                                        {{ __('pos.status_approved') }}
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item"
                                                            @if($status==='posted') disabled @endif
                                                            onclick="confirmStatus({{ $r->id }}, 'posted')">
                                                        {{ __('pos.status_posted') }}
                                                    </button>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button class="dropdown-item text-danger"
                                                            @if($status==='cancelled') disabled @endif
                                                            onclick="confirmStatus({{ $r->id }}, 'cancelled')">
                                                        {{ __('pos.status_cancelled') }}
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>

                                        {{-- Delete --}}
                                        <button type="button"
                                                class="btn btn-sm btn-soft btn-soft-danger"
                                                onclick="confirmDeletePurchase({{ $r->id }})"
                                                title="{{ __('pos.delete') }}">
                                            <i class="mdi mdi-trash-can-outline"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">{{ __('pos.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $rows->links() }}
            </div>
        </div>
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDeletePurchase(id){
            Swal.fire({
                title: '{{ __("pos.confirm_delete_title") }}',
                text: '{{ __("pos.confirm_delete_text") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#0d6efd',
                confirmButtonText: '{{ __("pos.confirm") }}',
                cancelButtonText: '{{ __("pos.cancel") }}'
            }).then((r)=>{
                if(r.isConfirmed){
                    Livewire.emit('deleteConfirmed', id);
                    Swal.fire('{{ __("pos.deleted") }}','{{ __("pos.deleted_ok") }}','success');
                }
            });
        }

        function confirmStatus(id, to){
            Swal.fire({
                title: '{{ __("pos.change_status") }}',
                text: '{{ __("pos.confirm_status_change") }}',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#0d6efd',
                confirmButtonText: '{{ __("pos.yes_change") }}',
                cancelButtonText: '{{ __("pos.cancel") }}'
            }).then((r)=>{
                if(r.isConfirmed){
                    Livewire.emit('statusChangeRequested', {id, to});
                    Swal.fire('{{ __("pos.done") }}','{{ __("pos.status_changed_ok") }}','success');
                }
            });
        }
    </script>
</div>

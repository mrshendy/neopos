<div>
    @php
        $resolveName = function($val){
            if (is_string($val) && strlen($val) && $val[0]==='{'){
                $arr = json_decode($val,true) ?: [];
                $loc = app()->getLocale();
                return $arr[$loc] ?? $arr['ar'] ?? $arr['en'] ?? $val;
            }
            return $val;
        };
    @endphp

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
        .badge-status.posted{background:#eff6ff;color:#1e40af;border:1px solid rgba(30,64,175,.15)}
        .badge-status.cancelled{background:#fef2f2;color:#991b1b;border:1px solid rgba(153,27,27,.15)}
    </style>

    <div class="card shadow-sm rounded-4 stylish-card">
        <div class="card-header bg-light d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0 fw-bold"><i class="mdi mdi-swap-horizontal-bold me-1"></i> {{ __('pos.trx_index_title') }}</h5>
                <div class="text-muted small">{{ __('pos.trx_index_subtitle') }}</div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('inv.trx.create') }}" class="btn btn-primary rounded-pill px-3 shadow-sm">
                    <i class="mdi mdi-plus-circle-outline me-1"></i> {{ __('pos.trx_new') }}
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
                    <input type="text" class="form-control" placeholder="{{ __('pos.search_ph_trx') }}" wire:model.debounce.400ms="search">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">{{ __('pos.type') }}</label>
                    <select class="form-select" wire:model="type">
                        <option value="">{{ __('pos.all') }}</option>
                        <option value="in">{{ __('pos.trx_type_in') }}</option>
                        <option value="out">{{ __('pos.trx_type_out') }}</option>
                        <option value="transfer">{{ __('pos.trx_type_transfer') }}</option>
                        <option value="direct_add">{{ __('pos.trx_type_direct_add') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">{{ __('pos.status') }}</label>
                    <select class="form-select" wire:model="status">
                        <option value="">{{ __('pos.all') }}</option>
                        <option value="draft">{{ __('pos.status_draft') }}</option>
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
                    <label class="form-label small text-muted mb-1">{{ __('pos.warehouse_any') }}</label>
                    <select class="form-select" wire:model="warehouse_id">
                        <option value="">{{ __('pos.all') }}</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}">{{ $resolveName($w->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                    <tr>
                        <th style="width:130px">{{ __('pos.trx_no') }}</th>
                        <th style="width:120px">{{ __('pos.trx_date') }}</th>
                        <th style="width:140px">{{ __('pos.trx_type') }}</th>
                        <th>{{ __('pos.from_warehouse') }}</th>
                        <th>{{ __('pos.to_warehouse') }}</th>
                        <th class="text-center" style="width:120px">{{ __('pos.status') }}</th>
                        <th style="width:140px">{{ __('pos.user') }}</th>
                        <th style="width:170px">{{ __('pos.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($rows as $r)
                        @php
                            $from = $resolveName($r->warehouseFrom->name ?? '—');
                            $to   = $resolveName($r->warehouseTo->name   ?? '—');
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $r->trx_no }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($r->trx_date)->format('Y-m-d') }}</td>
                            <td>
                                @switch($r->type)
                                    @case('in')         {{ __('pos.trx_type_in') }} @break
                                    @case('out')        {{ __('pos.trx_type_out') }} @break
                                    @case('transfer')   {{ __('pos.trx_type_transfer') }} @break
                                    @case('direct_add') {{ __('pos.trx_type_direct_add') }} @break
                                    @default {{ $r->type }}
                                @endswitch
                            </td>
                            <td>{{ $from }}</td>
                            <td>{{ $to }}</td>
                            <td class="text-center">
                                <span class="badge badge-status {{ $r->status }}">{{ __("pos.status_{$r->status}") }}</span>
                            </td>
                            <td>{{ $r->user->name ?? '—' }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('inv.trx.edit', $r->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="mdi mdi-pencil-outline"></i>
                                    </a>
                                    <a href="{{ route('inv.trx.create') }}?copy={{ $r->id }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="mdi mdi-content-copy"></i>
                                    </a>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="mdi mdi-flag-variant-outline"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#" wire:click.prevent="changeStatus({{ $r->id }}, 'draft')">{{ __('pos.status_draft') }}</a></li>
                                            <li><a class="dropdown-item" href="#" wire:click.prevent="changeStatus({{ $r->id }}, 'posted')">{{ __('pos.status_posted') }}</a></li>
                                            <li><a class="dropdown-item text-danger" href="#" wire:click.prevent="changeStatus({{ $r->id }}, 'cancelled')">{{ __('pos.status_cancelled') }}</a></li>
                                        </ul>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $r->id }})">
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


</div>

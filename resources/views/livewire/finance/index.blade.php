<div>
    <style>
        .sticky-filters{position:sticky;top:12px}
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
        .preview-chip{display:inline-flex;align-items:center;gap:.35rem;background:#f8f9fa;border:1px solid rgba(0,0,0,.06);border-radius:999px;padding:.25rem .6rem;font-size:.8rem;color:#6c757d}
    </style>

    {{-- ===== Filters Bar ===== --}}
    <div class="card shadow-sm rounded-4 mb-3 stylish-card">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-lg-3">
                    <label class="form-label mb-1"><i class="mdi mdi-magnify"></i> {{ __('pos.search') }}</label>
                    <input type="text" class="form-control" wire:model.debounce.400ms="search"
                           placeholder="{{ __('pos.ph_search_finance') }}">
                    <small class="text-muted">{{ __('pos.hint_search_finance') }}</small>
                </div>

                <div class="col-lg-2">
                    <label class="form-label mb-1">{{ __('pos.status') }}</label>
                    <select class="form-select" wire:model="status">
                        <option value="">{{ __('pos.all') }}</option>
                        <option value="active">{{ __('pos.status_active') }}</option>
                        <option value="inactive">{{ __('pos.status_inactive') }}</option>
                    </select>
                    <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $status ?: __('pos.all') }}</div>
                </div>

                <div class="col-lg-2">
                    <label class="form-label mb-1">{{ __('pos.branch') }}</label>
                    <input type="number" class="form-control" wire:model="branch_id" placeholder="{{ __('pos.ph_branch_id') }}">
                    <div class="preview-chip"><i class="mdi mdi-office-building"></i> {{ $branch_id ?: '—' }}</div>
                </div>

                <div class="col-lg-2">
                    <label class="form-label mb-1">{{ __('pos.date_from') }}</label>
                    <input type="date" class="form-control" wire:model="date_from">
                    <div class="preview-chip"><i class="mdi mdi-calendar-start"></i> {{ $date_from ?: '—' }}</div>
                </div>

                <div class="col-lg-2">
                    <label class="form-label mb-1">{{ __('pos.date_to') }}</label>
                    <input type="date" class="form-control" wire:model="date_to">
                    <div class="preview-chip"><i class="mdi mdi-calendar-end"></i> {{ $date_to ?: '—' }}</div>
                </div>

                <div class="col-lg-1">
                    <label class="form-label mb-1">{{ __('pos.per_page') }}</label>
                    <select class="form-select" wire:model="perPage">
                        <option>10</option><option>25</option><option>50</option><option>100</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Table ===== --}}
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

    <div class="card shadow-sm rounded-4 stylish-card">
        <div class="card-header bg-light fw-bold d-flex align-items-center justify-content-between">
            <span><i class="mdi mdi-safe me-1"></i> {{ __('pos.finance_title_index') }}</span>
            <div class="d-flex gap-2">
                <a href="{{ route('finance.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                    <i class="mdi mdi-plus"></i> {{ __('pos.btn_new') }}
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>{{ __('pos.col_name') }}</th>
                        <th>{{ __('pos.col_branch') }}</th>
                        <th>{{ __('pos.col_currency') }}</th>
                        <th>{{ __('pos.col_prefix') }}</th>
                        <th>{{ __('pos.col_next_no') }}</th>
                        <th>{{ __('pos.col_status') }}</th>
                        <th class="text-end">{{ __('pos.col_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $row)
                        <tr>
                            <td>{{ $row->id }}</td>
                            <td>{{ $resolveName($row->name) }}</td>
                            <td>{{ $row->branch_id ?: '—' }}</td>
                            <td>{{ $row->currency_id ?: '—' }}</td>
                            <td>{{ $row->receipt_prefix }}</td>
                            <td>{{ $row->next_number }}</td>
                            <td>
                                <span class="badge {{ $row->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $row->status === 'active' ? __('pos.status_active') : __('pos.status_inactive') }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('finance.show', $row->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="mdi mdi-eye-outline"></i> {{ __('pos.btn_show') }}
                                </a>
                                <a href="{{ route('finance.edit', $row->id) }}" class="btn btn-sm btn-outline-warning rounded-pill">
                                    <i class="mdi mdi-pencil-outline"></i> {{ __('pos.btn_edit') }}
                                </a>
                                <button wire:click="toggleStatus({{ $row->id }})" class="btn btn-sm btn-outline-secondary rounded-pill">
                                    <i class="mdi mdi-swap-horizontal"></i> {{ __('pos.btn_toggle_status') }}
                                </button>
                                <button onclick="confirmDelete({{ $row->id }})" class="btn btn-sm btn-outline-danger rounded-pill">
                                    <i class="mdi mdi-trash-can-outline"></i> {{ __('pos.btn_delete') }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="mdi mdi-information-outline"></i> {{ __('pos.no_data') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">{{ __('pos.pagination_info', ['from' => $items->firstItem() ?: 0, 'to' => $items->lastItem() ?: 0, 'total' => $items->total()]) }}</small>
            {{ $items->links() }}
        </div>
    </div>

    {{-- ✅ SweetAlert2 --}}
    <script>
    function confirmDelete(id) {
      Swal.fire({
        title: '{{ __('pos.alert_title') }}',
        text: '{{ __('pos.alert_text') }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#0d6efd',
        confirmButtonText: '{{ __('pos.alert_confirm') }}',
        cancelButtonText: '{{ __('pos.alert_cancel') }}'
      }).then((result) => {
        if (result.isConfirmed) {
          Livewire.emit('deleteConfirmed', id);
          Swal.fire('{{ __('pos.alert_deleted_title') }}', '{{ __('pos.alert_deleted_text') }}', 'success');
        }
      })
    }
    </script>
</div>

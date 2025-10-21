<div>
  <style>
    .stylish-card{border:1px solid rgba(0,0,0,.06)}
    .preview-chip{display:inline-flex;align-items:center;gap:.35rem;background:#f8f9fa;border:1px solid rgba(0,0,0,.06);border-radius:999px;padding:.25rem .6rem;font-size:.8rem;color:#6c757d}
  </style>

  {{-- Filters --}}
  <div class="card rounded-4 shadow-sm stylish-card mb-3">
    <div class="card-body">
      <div class="row g-3 align-items-end">
        <div class="col-lg-3">
          <label class="form-label mb-1"><i class="mdi mdi-magnify"></i> {{ __('pos.search') }}</label>
          <input type="text" class="form-control" wire:model.debounce.400ms="search" placeholder="{{ __('pos.ph_search_finset') }}">
          <small class="text-muted">{{ __('pos.hint_search_finset') }}</small>
        </div>
        <div class="col-lg-2">
          <label class="form-label mb-1">{{ __('pos.branch') }}</label>
          <input type="number" class="form-control" wire:model="branch_id" placeholder="{{ __('pos.ph_branch_id') }}">
          <div class="preview-chip"><i class="mdi mdi-office-building"></i> {{ $branch_id ?: '—' }}</div>
        </div>
        <div class="col-lg-2">
          <label class="form-label mb-1">{{ __('pos.warehouse') }}</label>
          <input type="number" class="form-control" wire:model="warehouse_id" placeholder="{{ __('pos.ph_warehouse_id') }}">
          <div class="preview-chip"><i class="mdi mdi-warehouse"></i> {{ $warehouse_id ?: '—' }}</div>
        </div>
        <div class="col-lg-2">
          <label class="form-label mb-1">{{ __('pos.available') }}</label>
          <select class="form-select" wire:model="is_available">
            <option value="">{{ __('pos.all') }}</option>
            <option value="1">{{ __('pos.yes') }}</option>
            <option value="0">{{ __('pos.no') }}</option>
          </select>
          <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> 
            {{ $is_available === '' ? __('pos.all') : ($is_available ? __('pos.yes') : __('pos.no')) }}
          </div>
        </div>
        <div class="col-lg-1">
          <label class="form-label mb-1">{{ __('pos.per_page') }}</label>
          <select class="form-select" wire:model="perPage">
            <option>10</option><option>25</option><option>50</option><option>100</option>
          </select>
        </div>
        <div class="col-lg-2 text-end">
          <a href="{{ route('finance_settings.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
            <i class="mdi mdi-plus"></i> {{ __('pos.btn_new') }}
          </a>
        </div>
      </div>
    </div>
  </div>

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

  {{-- Table --}}
  <div class="card rounded-4 shadow-sm stylish-card">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>{{ __('pos.col_name') }}</th>
            <th>{{ __('pos.col_branch') }}</th>
            <th>{{ __('pos.col_warehouse') }}</th>
            <th>{{ __('pos.col_currency') }}</th>
            <th>{{ __('pos.col_allow_negative') }}</th>
            <th>{{ __('pos.available') }}</th>
            <th class="text-end">{{ __('pos.col_actions') }}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $row)
            <tr>
              <td>{{ $row->id }}</td>
              <td>{{ $resolveName($row->name) }}</td>
              <td>{{ $row->branch_id ?: '—' }}</td>
              <td>{{ $row->warehouse_id ?: '—' }}</td>
              <td>{{ $row->currency_id ?: '—' }}</td>
              <td>
                <span class="badge {{ $row->allow_negative_stock ? 'bg-warning' : 'bg-secondary' }}">
                  {{ $row->allow_negative_stock ? __('pos.yes') : __('pos.no') }}
                </span>
              </td>
              <td>
                <span class="badge {{ $row->is_available ? 'bg-success' : 'bg-danger' }}">
                  {{ $row->is_available ? __('pos.yes') : __('pos.no') }}
                </span>
              </td>
              <td class="text-end">
                <a href="{{ route('finance_settings.show', $row->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                  <i class="mdi mdi-eye-outline"></i> {{ __('pos.btn_show') }}
                </a>
                <a href="{{ route('finance_settings.edit', $row->id) }}" class="btn btn-sm btn-outline-warning rounded-pill">
                  <i class="mdi mdi-pencil-outline"></i> {{ __('pos.btn_edit') }}
                </a>
                <button wire:click="toggleAvailable({{ $row->id }})" class="btn btn-sm btn-outline-secondary rounded-pill">
                  <i class="mdi mdi-toggle-switch"></i> {{ __('pos.btn_toggle_status') }}
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

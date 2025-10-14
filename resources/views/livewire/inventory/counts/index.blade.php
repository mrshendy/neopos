<div>
  @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
      <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- نموذج إنشاء جرد جديد --}}
  <div class="card border-0 shadow-lg rounded-4 stylish-card mb-4">
    <div class="card-header bg-light fw-bold">
      <i class="mdi mdi-clipboard-plus-outline me-2"></i> {{ __('pos.start_new_count') }}
    </div>

    <div class="card-body p-4">
      <form wire:submit.prevent="startCount" class="row g-3">
        <div class="col-md-4">
          <label class="form-label fw-bold">{{ __('pos.warehouse') }}</label>
          <select class="form-select" wire:model.defer="warehouse_id">
            <option value="">{{ __('pos.choose') }}</option>
            @foreach($warehouses as $w)
              <option value="{{ $w->id }}">
                {{ app()->getLocale()=='ar' ? ($w->name['ar']??'') : ($w->name['en']??'') }}
              </option>
            @endforeach
          </select>
          @error('warehouse_id') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold">{{ __('pos.policy') }}</label>
          <select class="form-select" wire:model.defer="policy">
            <option value="periodic">{{ __('pos.count_periodic') }}</option>
            <option value="spot">{{ __('pos.count_spot') }}</option>
          </select>
          @error('policy') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
          <label class="form-label fw-bold">{{ __('pos.notes') }}</label>
          <input type="text" class="form-control" wire:model.defer="notes" placeholder="{{ __('pos.hint_notes') }}">
        </div>

        <div class="col-12 text-end">
          <button class="btn btn-success rounded-pill px-4 shadow-sm">
            <i class="mdi mdi-play-circle-outline"></i> {{ __('pos.btn_start') }}
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- جدول الجردات السابقة --}}
  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-light fw-bold">
      <i class="mdi mdi-history me-2"></i> {{ __('pos.previous_counts') }}
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>{{ __('pos.warehouse') }}</th>
              <th>{{ __('pos.policy') }}</th>
              <th>{{ __('pos.status') }}</th>
              <th>{{ __('pos.started_at') }}</th>
              <th>{{ __('pos.notes') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($counts as $c)
              <tr>
                <td>{{ $c->id }}</td>
                <td>{{ $c->warehouse? (app()->getLocale()=='ar'?($c->warehouse->name['ar']??''):($c->warehouse->name['en']??'')) : '-' }}</td>
                <td>{{ __('pos.count_'.$c->policy) }}</td>
                <td>
                  <span class="badge bg-{{ $c->status=='open'?'warning':'success' }}">
                    {{ __('pos.'.$c->status) }}
                  </span>
                </td>
                <td>{{ \Carbon\Carbon::parse($c->started_at)->format('Y-m-d H:i') }}</td>
                <td>{{ $c->notes }}</td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted">{{ __('pos.no_data') }}</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer text-end">
      {{ $counts->onEachSide(1)->links() }}
    </div>
  </div>
</div>

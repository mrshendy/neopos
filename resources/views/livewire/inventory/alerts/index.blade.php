<div>
  <div class="card border-0 shadow-lg rounded-4 stylish-card mb-4">
    <div class="card-header bg-light fw-bold">
      <i class="mdi mdi-bell-outline me-2"></i> {{ __('pos.inventory_alerts_title') }}
    </div>
    <div class="card-body p-3">
      <div class="row g-3 mb-3">
        <div class="col-md-3">
          <label class="form-label fw-bold"><i class="mdi mdi-filter-variant"></i> {{ __('pos.alert_type') }}</label>
          <select class="form-select" wire:model="type">
            <option value="reorder">{{ __('pos.alert_reorder') }}</option>
            <option value="expiry">{{ __('pos.alert_expiry') }}</option>
            <option value="expired">{{ __('pos.alert_expired') }}</option>
          </select>
        </div>
      </div>

      {{-- قائمة التنبيهات --}}
      <div class="table-responsive">
        <table class="table align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>{{ __('pos.item') }}</th>
              <th>{{ __('pos.warehouse') }}</th>
              <th>{{ __('pos.qty') }}</th>
              <th>{{ __('pos.alert_message') }}</th>
              <th>{{ __('pos.created_at') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($alerts as $a)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $a->item ?? '-' }}</td>
                <td>{{ $a->warehouse ?? '-' }}</td>
                <td>{{ $a->qty ?? '-' }}</td>
                <td>
                  @if($type=='reorder')
                    <span class="text-warning"><i class="mdi mdi-alert-outline"></i> {{ __('pos.reorder_alert_msg', ['item'=>$a->item]) }}</span>
                  @elseif($type=='expiry')
                    <span class="text-danger"><i class="mdi mdi-timer-outline"></i> {{ __('pos.expiry_alert_msg', ['item'=>$a->item]) }}</span>
                  @else
                    <span class="text-danger"><i class="mdi mdi-timer-off-outline"></i> {{ __('pos.expired_alert_msg', ['item'=>$a->item]) }}</span>
                  @endif
                </td>
                <td>{{ $a->created_at ?? now()->format('Y-m-d H:i') }}</td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted">{{ __('pos.no_alerts') }}</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div>
  @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
      <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card border-0 shadow-lg rounded-4 stylish-card">
    <div class="card-header bg-light fw-bold">
      <i class="mdi mdi-cog-outline me-2"></i> {{ __('pos.inventory_settings_title') }}
    </div>

    <div class="card-body p-4">
      <form wire:submit.prevent="save" class="row g-3">
        {{-- سياسة السالب --}}
        <div class="col-md-6">
          <label class="form-label fw-bold">
            <i class="mdi mdi-minus-circle-outline text-danger me-1"></i> {{ __('pos.negative_stock_policy') }}
          </label>
          <select class="form-select" wire:model.defer="negative_stock_policy">
            <option value="block">{{ __('pos.policy_block') }}</option>
            <option value="warn">{{ __('pos.policy_warn') }}</option>
          </select>
          <small class="text-muted d-block">{{ __('pos.hint_negative_stock_policy') }}</small>
          <div class="mt-1 text-primary"><i class="mdi mdi-eye-outline"></i> {{ $negative_stock_policy }}</div>
          @error('negative_stock_policy') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        {{-- تنبيه انتهاء الصلاحية --}}
        <div class="col-md-6">
          <label class="form-label fw-bold">
            <i class="mdi mdi-timer-outline text-warning me-1"></i> {{ __('pos.expiry_alert_days') }}
          </label>
          <input type="number" class="form-control" wire:model.defer="expiry_alert_days" min="1" max="3650">
          <small class="text-muted d-block">{{ __('pos.hint_expiry_alert_days') }}</small>
          <div class="mt-1 text-primary"><i class="mdi mdi-eye-outline"></i> {{ $expiry_alert_days }}</div>
          @error('expiry_alert_days') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        {{-- ترقيم الحركات --}}
        <div class="col-md-12">
          <label class="form-label fw-bold">
            <i class="mdi mdi-pound-box-outline text-info me-1"></i> {{ __('pos.transaction_sequence_pattern') }}
          </label>
          <input type="text" class="form-control" wire:model.defer="transaction_sequences.pattern"
                 placeholder="INV-TRX-{YYYY}-{####}">
          <small class="text-muted d-block">{{ __('pos.hint_transaction_sequence') }}</small>
          <div class="mt-1 text-primary"><i class="mdi mdi-eye-outline"></i> {{ $transaction_sequences['pattern'] }}</div>
          @error('transaction_sequences.pattern') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        {{-- حفظ --}}
        <div class="col-12 text-end mt-3">
          <button class="btn btn-success rounded-pill px-4 shadow-sm">
            <i class="mdi mdi-content-save"></i> {{ __('pos.btn_save') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="page-wrap">

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

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="mb-1 fw-bold">
                <i class="mdi mdi-cog-outline me-2"></i> {{ __('inventory.settings_title') }}
            </h3>
            <div class="text-muted small">{{ __('inventory.settings_sub') }}</div>
        </div>
    </div>

    <form wire:submit.prevent="save" class="card shadow-sm rounded-4">
        <div class="card-body">
            <div class="row g-4">

                {{-- سياسة السالب --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">{{ __('inventory.negative_stock_policy') }}</label>
                    <select class="form-select" wire:model="negative_stock_policy">
                        <option value="block">{{ __('inventory.policy_block') }}</option>
                        <option value="warn">{{ __('inventory.policy_warn') }}</option>
                    </select>
                    @error('negative_stock_policy') <small class="text-danger">{{ $message }}</small> @enderror
                    <small class="text-muted d-block">{{ __('inventory.help_negative_stock_policy_short') }}</small>
                </div>

                {{-- أيام تنبيه الصلاحية --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">{{ __('inventory.expiry_alert_days') }}</label>
                    <input type="number" min="0" max="3650" class="form-control" wire:model.defer="expiry_alert_days" placeholder="مثال: 30">
                    @error('expiry_alert_days') <small class="text-danger">{{ $message }}</small> @enderror
                    <small class="text-muted d-block">{{ __('inventory.help_expiry_alert_days') }}</small>
                </div>

                {{-- نمط ترقيم الحركة --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">{{ __('inventory.transaction_pattern') }}</label>
                    <input type="text" class="form-control" wire:model.defer="transaction_sequences.pattern" placeholder="INV-TRX-{YYYY}-{####}">
                    @error('transaction_sequences.pattern') <small class="text-danger">{{ $message }}</small> @enderror
                    <small class="text-muted d-block">
                        {{ __('inventory.help_transaction_pattern') }}
                    </small>
                    <div class="mt-1 small text-primary">
                        <i class="mdi mdi-eye-outline"></i>
                        {{ __('inventory.example') }}:
                        {{ str_replace(['{YYYY}','{YY}','{MM}','{DD}','{####}'], [date('Y'), date('y'), date('m'), date('d'), '0001'], $transaction_sequences['pattern'] ?? 'INV-TRX-{YYYY}-{####}') }}
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                <i class="mdi mdi-content-save-outline"></i> {{ __('inventory.btn_save_settings') }}
            </button>
        </div>
    </form>
</div>

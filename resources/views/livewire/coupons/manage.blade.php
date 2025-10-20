<div class="page-wrap">

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-alert-circle-outline me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        .preview-chip{display:inline-flex;align-items:center;gap:.35rem;background:#f8f9fa;border:1px solid rgba(0,0,0,.06);border-radius:999px;padding:.25rem .6rem;font-size:.8rem;color:#6c757d}
        .help{font-size:.8rem;color:#6c757d}
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
    </style>

    <form wire:submit.prevent="save" class="row g-3">
        <div class="col-12">
            <div class="card rounded-4 shadow-sm stylish-card">
                <div class="card-header bg-light fw-bold">
                    <i class="mdi mdi-ticket-percent-outline me-2"></i> {{ $coupon ? __('pos.edit_coupon') : __('pos.create_coupon') }}
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="mdi mdi-alphabetical"></i> {{ __('pos.name_ar') }}</label>
                            <input type="text" class="form-control @error('name.ar') is-invalid @enderror" wire:model.lazy="name.ar" placeholder="{{ __('pos.ph_name_ar') }}">
                            @error('name.ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="mt-1 preview-chip"><i class="mdi mdi-translate"></i> {{ $name['ar'] ?: __('pos.no_value') }}</div>
                            <small class="help">{{ __('pos.hint_name') }}</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="mdi mdi-alphabetical-variant"></i> {{ __('pos.name_en') }}</label>
                            <input type="text" class="form-control @error('name.en') is-invalid @enderror" wire:model.lazy="name.en" placeholder="{{ __('pos.ph_name_en') }}">
                            @error('name.en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="mt-1 preview-chip"><i class="mdi mdi-translate-variant"></i> {{ $name['en'] ?: __('pos.no_value') }}</div>
                            <small class="help">{{ __('pos.hint_name') }}</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="mdi mdi-tag-outline"></i> {{ __('pos.type') }}</label>
                            <select class="form-select" wire:model="type">
                                <option value="percentage">{{ __('pos.type_percentage') }}</option>
                                <option value="fixed">{{ __('pos.type_fixed') }}</option>
                            </select>
                            <div class="mt-1 preview-chip"><i class="mdi mdi-shape-outline"></i> {{ __('pos.type_'.$type) }}</div>
                            <small class="help">{{ __('pos.hint_type_offer') }}</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="mdi mdi-percent"></i> {{ __('pos.discount_value') }}</label>
                            <input type="number" step="0.01" class="form-control @error('discount_value') is-invalid @enderror" wire:model.lazy="discount_value" placeholder="0">
                            @error('discount_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="mt-1 preview-chip"><i class="mdi mdi-cash"></i> {{ $discount_value ?? 0 }}</div>
                            <small class="help">{{ __('pos.hint_discount_value') }}</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('pos.status') }}</label>
                            <select class="form-select" wire:model="status">
                                <option value="active">{{ __('pos.status_active') }}</option>
                                <option value="paused">{{ __('pos.status_paused') }}</option>
                                <option value="expired">{{ __('pos.status_expired') }}</option>
                            </select>
                            <div class="mt-1 preview-chip"><i class="mdi mdi-traffic-light"></i> {{ __('pos.status_'.$status) }}</div>
                            <small class="help">{{ __('pos.hint_status') }}</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('pos.date_from') }}</label>
                            <input type="datetime-local" class="form-control" wire:model.lazy="start_at">
                            <div class="mt-1 preview-chip">{{ $start_at ?: __('pos.no_value') }}</div>
                            <small class="help">{{ __('pos.hint_period') }}</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('pos.date_to') }}</label>
                            <input type="datetime-local" class="form-control @error('end_at') is-invalid @enderror" wire:model.lazy="end_at">
                            @error('end_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="mt-1 preview-chip">{{ $end_at ?: __('pos.no_value') }}</div>
                            <small class="help">{{ __('pos.hint_period') }}</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Max Uses / Customer</label>
                            <input type="number" class="form-control @error('max_uses_per_customer') is-invalid @enderror" wire:model.lazy="max_uses_per_customer" min="1">
                            @error('max_uses_per_customer') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="mt-1 preview-chip">{{ $max_uses_per_customer }}</div>
                            <small class="help">الحد الأقصى لاستخدام العميل الواحد.</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Max Total Uses</label>
                            <input type="number" class="form-control @error('max_total_uses') is-invalid @enderror" wire:model.lazy="max_total_uses" min="1">
                            @error('max_total_uses') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="mt-1 preview-chip">{{ $max_total_uses ?? '∞' }}</div>
                            <small class="help">اتركها فارغة للسماح بعدد غير محدود.</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('pos.stackable') }}</label>
                            <select class="form-select" wire:model="is_stackable">
                                <option value="0">{{ __('pos.no') }}</option>
                                <option value="1">{{ __('pos.yes') }}</option>
                            </select>
                            <div class="mt-1 preview-chip">{{ $is_stackable ? __('pos.yes') : __('pos.no') }}</div>
                            <small class="help">{{ __('pos.hint_stackable') }}</small>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold"><i class="mdi mdi-text-long"></i> {{ __('pos.description') }}</label>
                            <textarea class="form-control mb-1" wire:model.lazy="description.{{ app()->getLocale() }}" rows="3" placeholder="{{ __('pos.ph_description') }}"></textarea>
                            <div class="mt-1 preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $description[app()->getLocale()] ?? __('pos.no_value') }}</div>
                            <small class="help">{{ __('pos.hint_description') }}</small>
                        </div>

                    </div>
                </div>
                <div class="card-footer d-flex gap-2">
                    <a href="{{ route('coupons.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-arrow-left"></i> {{ __('pos.back') }}
                    </a>
                    <button class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-content-save-outline"></i> {{ __('pos.save') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<div>
    <style>
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
        .field-block{position:relative}
        .preview-chip{display:inline-flex;align-items:center;gap:.35rem;background:#f8f9fa;border:1px solid rgba(0,0,0,.06);border-radius:999px;padding:.25rem .6rem;font-size:.8rem;color:#6c757d;margin-top:.4rem}
        .help{font-size:.8rem;color:#6c757d}
    </style>

    <form wire:submit.prevent="save" class="row g-3">
        <div class="col-12">
            <div class="card rounded-4 shadow-sm stylish-card">
                <div class="card-header bg-light fw-bold">
                    <i class="mdi mdi-safe me-1"></i>
                    {{ $finance_id ? __('pos.finance_title_edit') : __('pos.finance_title_create') }}
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        {{-- الاسم (AR) --}}
                        <div class="col-lg-6 field-block">
                            <label class="form-label">{{ __('pos.f_name_ar') }}</label>
                            <input type="text" class="form-control" wire:model.defer="name.ar" placeholder="{{ __('pos.ph_name_ar') }}">
                            <small class="help">{{ __('pos.help_name_ar') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $name['ar'] ?: '—' }}</div>
                            @error('name.ar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- الاسم (EN) --}}
                        <div class="col-lg-6 field-block">
                            <label class="form-label">{{ __('pos.f_name_en') }}</label>
                            <input type="text" class="form-control" wire:model.defer="name.en" placeholder="{{ __('pos.ph_name_en') }}">
                            <small class="help">{{ __('pos.help_name_en') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $name['en'] ?: '—' }}</div>
                            @error('name.en') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- الفرع --}}
                        <div class="col-lg-3 field-block">
                            <label class="form-label">{{ __('pos.f_branch') }}</label>
                            <input type="number" class="form-control" wire:model.defer="branch_id" placeholder="{{ __('pos.ph_branch_id') }}">
                            <small class="help">{{ __('pos.help_branch') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-office-building"></i> {{ $branch_id ?: '—' }}</div>
                            @error('branch_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- العملة --}}
                        <div class="col-lg-3 field-block">
                            <label class="form-label">{{ __('pos.f_currency') }}</label>
                            <input type="number" class="form-control" wire:model.defer="currency_id" placeholder="{{ __('pos.ph_currency_id') }}">
                            <small class="help">{{ __('pos.help_currency') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-cash-multiple"></i> {{ $currency_id ?: '—' }}</div>
                            @error('currency_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- البادئة --}}
                        <div class="col-lg-3 field-block">
                            <label class="form-label">{{ __('pos.f_prefix') }}</label>
                            <input type="text" class="form-control" wire:model.defer="receipt_prefix" placeholder="{{ __('pos.ph_prefix') }}">
                            <small class="help">{{ __('pos.help_prefix') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-format-letter-matches"></i> {{ $receipt_prefix }}</div>
                            @error('receipt_prefix') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- أول رقم --}}
                        <div class="col-lg-3 field-block">
                            <label class="form-label">{{ __('pos.f_next_no') }}</label>
                            <input type="number" class="form-control" wire:model.defer="next_number" min="1" placeholder="1">
                            <small class="help">{{ __('pos.help_next_no') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-numeric"></i> {{ $next_number }}</div>
                            @error('next_number') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- يسمح بالسالب --}}
                        <div class="col-lg-3">
                            <label class="form-label d-block">{{ __('pos.f_allow_negative') }}</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" wire:model.defer="allow_negative" id="negSwitch">
                                <label class="form-check-label" for="negSwitch">{{ __('pos.lbl_switch') }}</label>
                            </div>
                            <div class="preview-chip"><i class="mdi mdi-minus-circle-outline"></i> {{ $allow_negative ? __('pos.yes') : __('pos.no') }}</div>
                        </div>

                        {{-- الحالة --}}
                        <div class="col-lg-3">
                            <label class="form-label">{{ __('pos.f_status') }}</label>
                            <select class="form-select" wire:model.defer="status">
                                <option value="active">{{ __('pos.status_active') }}</option>
                                <option value="inactive">{{ __('pos.status_inactive') }}</option>
                            </select>
                            <div class="preview-chip"><i class="mdi mdi-flag-outline"></i>
                                {{ $status === 'active' ? __('pos.status_active') : __('pos.status_inactive') }}
                            </div>
                            @error('status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- ملاحظات (AR) --}}
                        <div class="col-12 field-block">
                            <label class="form-label">{{ __('pos.f_notes_ar') }}</label>
                            <textarea class="form-control" rows="2" wire:model.defer="notes.ar" placeholder="{{ __('pos.ph_notes_ar') }}"></textarea>
                            <small class="help">{{ __('pos.help_notes') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $notes['ar'] ?: '—' }}</div>
                            @error('notes.ar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- ملاحظات (EN) --}}
                        <div class="col-12 field-block">
                            <label class="form-label">{{ __('pos.f_notes_en') }}</label>
                            <textarea class="form-control" rows="2" wire:model.defer="notes.en" placeholder="{{ __('pos.ph_notes_en') }}"></textarea>
                            <small class="help">{{ __('pos.help_notes') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $notes['en'] ?: '—' }}</div>
                            @error('notes.en') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex gap-2">
                    <a href="{{ route('finance.index') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') }}
                    </a>
                    <button class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-content-save-outline"></i> {{ __('pos.btn_save') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

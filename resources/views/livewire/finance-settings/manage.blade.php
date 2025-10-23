<div>
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
        .field-block{position:relative}
        .field-block label{font-weight:600}
        .help{font-size:.8rem;color:#6c757d}
        .preview-chip{display:inline-flex;align-items:center;gap:.35rem;background:#f8f9fa;border:1px solid rgba(0,0,0,.06);border-radius:999px;padding:.25rem .6rem;font-size:.8rem;color:#6c757d;margin-top:.4rem}
        .section-title{font-weight:700;color:#6c757d;text-transform:uppercase;font-size:.9rem;letter-spacing:.4px}
        .soft-badge{font-size:.75rem}
        .table thead th{white-space:nowrap}
        .table td, .table th{vertical-align:middle}
    </style>

    <form wire:submit.prevent="save" class="row g-3">
        <div class="col-12">
            <div class="card rounded-4 shadow-sm stylish-card">
                <div class="card-header bg-light fw-bold d-flex align-items-center justify-content-between">
                    <span><i class="mdi mdi-warehouse-cog me-1"></i> {{ __('pos.finset_title') }}</span>
                    <span class="badge bg-secondary soft-badge">{{ $settings_id ? __('pos.editing') : __('pos.creating') }}</span>
                </div>

                <div class="card-body">
                    {{-- ====== الاسم (AR/EN) ====== --}}
                    <div class="row g-3">
                        <div class="col-lg-6 field-block">
                            <label class="form-label"><i class="mdi mdi-alphabetical-variant me-1"></i> {{ __('pos.finset_name_ar') }}</label>
                            <input type="text" class="form-control" wire:model.defer="name.ar" placeholder="{{ __('pos.finset_ph_name_ar') }}">
                            <small class="help">{{ __('pos.finset_help_name_ar') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $name['ar'] ?: '—' }}</div>
                            @error('name.ar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-lg-6 field-block">
                            <label class="form-label"><i class="mdi mdi-alphabetical-variant me-1"></i> {{ __('pos.finset_name_en') }}</label>
                            <input type="text" class="form-control" wire:model.defer="name.en" placeholder="{{ __('pos.finset_ph_name_en') }}">
                            <small class="help">{{ __('pos.finset_help_name_en') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $name['en'] ?: '—' }}</div>
                            @error('name.en') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="divider my-3"></div>

                    {{-- ====== إعدادات أساسية ====== --}}
                    <div class="row g-3">
                        {{-- نوع الخزينة: رئيسية / فرعية --}}
                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-home-group me-1"></i> {{ __('pos.finset_cashbox_type') }}</label>
                            <select class="form-select" wire:model.defer="cashbox_type">
                                <option value="main">{{ __('pos.finset_cashbox_type_main') }}</option>
                                <option value="sub">{{ __('pos.finset_cashbox_type_sub') }}</option>
                            </select>
                            <small class="help">{{ __('pos.finset_help_cashbox_type') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i>
                                {{ $cashbox_type === 'main' ? __('pos.finset_cashbox_type_main') : __('pos.finset_cashbox_type_sub') }}
                            </div>
                            @error('cashbox_type') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- العملة --}}
                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-cash-multiple me-1"></i> {{ __('pos.finset_currency') }}</label>
                            <input type="number" class="form-control" wire:model.defer="currency_id" placeholder="{{ __('pos.finset_ph_currency') }}">
                            <small class="help">{{ __('pos.finset_help_currency') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-cash-multiple"></i> {{ $currency_id ?: '—' }}</div>
                            @error('currency_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="divider my-3"></div>

                    {{-- ====== مفاتيح التحكم ====== --}}
                    <div class="row g-3">
                        <div class="col-lg-3">
                            <label class="form-label d-block"><i class="mdi mdi-toggle-switch me-1"></i> {{ __('pos.finset_is_available') }}</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="isAvailable" wire:model.defer="is_available">
                                <label class="form-check-label" for="isAvailable">{{ $is_available ? __('pos.yes') : __('pos.no') }}</label>
                            </div>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $is_available ? __('pos.enabled') : __('pos.disabled') }}</div>
                            @error('is_available') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-3">
                            <label class="form-label d-block"><i class="mdi mdi-minus-circle-outline me-1"></i> {{ __('pos.finset_allow_negative_stock') }}</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="negStock" wire:model.defer="allow_negative_stock">
                                <label class="form-check-label" for="negStock">{{ $allow_negative_stock ? __('pos.yes') : __('pos.no') }}</label>
                            </div>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $allow_negative_stock ? __('pos.enabled') : __('pos.disabled') }}</div>
                            @error('allow_negative_stock') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-3 field-block">
                            <label class="form-label"><i class="mdi mdi-timer-sand-empty me-1"></i> {{ __('pos.finset_return_window_days') }}</label>
                            <input type="number" min="0" class="form-control" wire:model.defer="return_window_days" placeholder="0">
                            <small class="help">{{ __('pos.finset_help_return_window_days') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-calendar-clock"></i> {{ $return_window_days }}</div>
                            @error('return_window_days') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-3">
                            <label class="form-label d-block"><i class="mdi mdi-shield-check-outline me-1"></i> {{ __('pos.finset_require_return_approval') }}</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="reqApproval" wire:model.defer="require_return_approval">
                                <label class="form-check-label" for="reqApproval">{{ $require_return_approval ? __('pos.yes') : __('pos.no') }}</label>
                            </div>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $require_return_approval ? __('pos.enabled') : __('pos.disabled') }}</div>
                            @error('require_return_approval') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-3 field-block">
                            <label class="form-label"><i class="mdi mdi-currency-usd me-1"></i> {{ __('pos.finset_approval_over_amount') }}</label>
                            <input type="number" min="0" step="0.01" class="form-control" wire:model.defer="approval_over_amount" placeholder="0.00">
                            <small class="help">{{ __('pos.finset_help_approval_over_amount') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-cash"></i> {{ $approval_over_amount !== null ? $approval_over_amount : '—' }}</div>
                            @error('approval_over_amount') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="divider my-3"></div>

                    {{-- ====== ترقيم الارتجاع ====== --}}
                    <div class="row g-3">
                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-format-letter-matches me-1"></i> {{ __('pos.finset_receipt_prefix') }}</label>
                            <input type="text" class="form-control" wire:model.defer="receipt_prefix" placeholder="RET">
                            <small class="help">{{ __('pos.finset_help_receipt_prefix') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $receipt_prefix }}</div>
                            @error('receipt_prefix') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-numeric me-1"></i> {{ __('pos.finset_next_return_number') }}</label>
                            <input type="number" min="1" class="form-control" wire:model.defer="next_return_number" placeholder="1">
                            <small class="help">{{ __('pos.finset_help_next_return_number') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-numeric"></i> {{ $next_return_number }}</div>
                            @error('next_return_number') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="divider my-3"></div>

                    {{-- ====== ملاحظات ====== --}}
                    <div class="row g-3">
                        <div class="col-12 field-block">
                            <label class="form-label"><i class="mdi mdi-note-text-outline me-1"></i> {{ __('pos.finset_notes_ar') }}</label>
                            <textarea class="form-control" rows="2" wire:model.defer="notes.ar" placeholder="{{ __('pos.finset_ph_notes_ar') }}"></textarea>
                            <small class="help">{{ __('pos.finset_help_notes') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $notes['ar'] ?: '—' }}</div>
                            @error('notes.ar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 field-block">
                            <label class="form-label"><i class="mdi mdi-note-text-outline me-1"></i> {{ __('pos.finset_notes_en') }}</label>
                            <textarea class="form-control" rows="2" wire:model.defer="notes.en" placeholder="{{ __('pos.finset_ph_notes_en') }}"></textarea>
                            <small class="help">{{ __('pos.finset_help_notes') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $notes['en'] ?: '—' }}</div>
                            @error('notes.en') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="divider my-3"></div>

                    {{-- ====== حدود المستخدمين (الحد الأقصى للارتجاع) ====== --}}
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="section-title"><i class="mdi mdi-account-cog-outline me-1"></i> {{ __('pos.finset_user_limits_section') }}</div>
                        <button type="button" class="btn btn-success rounded-pill px-4 shadow-sm" wire:click="addUserLimit">
                            <i class="mdi mdi-plus"></i> {{ __('pos.btn_add_row') }}
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:210px">{{ __('pos.finset_ul_user') }}</th>
                                    <th style="width:160px">{{ __('pos.finset_ul_daily_count') }}</th>
                                    <th style="width:180px">{{ __('pos.finset_ul_daily_amount') }}</th>
                                    <th style="width:160px">{{ __('pos.finset_ul_require_supervisor') }}</th>
                                    <th style="width:120px">{{ __('pos.finset_ul_active') }}</th>
                                    <th class="text-end" style="width:100px">{{ __('pos.col_actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($userLimits as $idx => $ul)
                                    <tr>
                                        {{-- ✅ دروب منيو بأسماء المستخدمين --}}
                                        <td>
                                            <select class="form-select"
                                                    wire:model.defer="userLimits.{{ $idx }}.user_id">
                                                <option value="">{{ __('pos.finset_ul_ph_user') }}</option>
                                                @foreach($users as $u)
                                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                                @endforeach
                                            </select>
                                            <small class="help">{{ __('pos.finset_ul_help_user') }}</small>

                                            <div class="preview-chip">
                                                <i class="mdi mdi-account-outline"></i>
                                                @php
                                                    $sel = collect($users)->firstWhere('id', (int)($ul['user_id'] ?? 0));
                                                @endphp
                                                {{ $sel->name ?? '—' }}
                                            </div>

                                            @error("userLimits.$idx.user_id") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                        </td>

                                        <td>
                                            <input type="number" min="0" class="form-control"
                                                   wire:model.defer="userLimits.{{ $idx }}.daily_count_limit" placeholder="0">
                                            <small class="help">{{ __('pos.finset_ul_help_daily_count') }}</small>
                                            <div class="preview-chip"><i class="mdi mdi-counter"></i> {{ $ul['daily_count_limit'] ?? '—' }}</div>
                                            @error("userLimits.$idx.daily_count_limit") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                        </td>

                                        <td>
                                            <input type="number" min="0" step="0.01" class="form-control"
                                                   wire:model.defer="userLimits.{{ $idx }}.daily_amount_limit" placeholder="0.00">
                                            <small class="help">{{ __('pos.finset_ul_help_daily_amount') }}</small>
                                            <div class="preview-chip"><i class="mdi mdi-cash"></i> {{ $ul['daily_amount_limit'] ?? '—' }}</div>
                                            @error("userLimits.$idx.daily_amount_limit") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                        </td>

                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                       wire:model.defer="userLimits.{{ $idx }}.require_supervisor" id="sup{{ $idx }}">
                                                <label class="form-check-label" for="sup{{ $idx }}">{{ ($ul['require_supervisor'] ?? false) ? __('pos.yes') : __('pos.no') }}</label>
                                            </div>
                                            <div class="preview-chip"><i class="mdi mdi-shield-account-outline"></i> {{ ($ul['require_supervisor'] ?? false) ? __('pos.yes') : __('pos.no') }}</div>
                                            @error("userLimits.$idx.require_supervisor") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                        </td>

                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                       wire:model.defer="userLimits.{{ $idx }}.active" id="act{{ $idx }}">
                                                <label class="form-check-label" for="act{{ $idx }}">{{ ($ul['active'] ?? true) ? __('pos.enabled') : __('pos.disabled') }}</label>
                                            </div>
                                            <div class="preview-chip"><i class="mdi mdi-power"></i> {{ ($ul['active'] ?? true) ? __('pos.enabled') : __('pos.disabled') }}</div>
                                            @error("userLimits.$idx.active") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                        </td>

                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill"
                                                    onclick="confirmRemoveLimit({{ $idx }})">
                                                <i class="mdi mdi-trash-can-outline"></i> {{ __('pos.btn_remove_row') }}
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">
                                            <i class="mdi mdi-information-outline me-1"></i>{{ __('pos.finset_ul_no_rows') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer d-flex gap-2">
                    <a href="{{ route('finance_settings.index') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') }}
                    </a>
                    <button class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="mdi mdi-content-save-outline"></i> {{ __('pos.btn_save') }}
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- SweetAlert2: تأكيد حذف صف حد المستخدم --}}
    <script>
        function confirmRemoveLimit(idx){
            Swal.fire({
                title: '{{ __('pos.alert_title') }}',
                text:  '{{ __('pos.alert_text') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#0d6efd',
                confirmButtonText: '{{ __('pos.alert_confirm') }}',
                cancelButtonText: '{{ __('pos.alert_cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.find('{{ $_instance->id }}').call('removeUserLimit', idx);
                    Swal.fire('{{ __('pos.alert_deleted_title') }}', '{{ __('pos.alert_deleted_text') }}', 'success');
                }
            })
        }
    </script>
</div>

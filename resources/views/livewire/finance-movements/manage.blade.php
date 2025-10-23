<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        .stylish-card{border:1px solid rgba(0,0,0,.06)}
        .field-block{position:relative}
        .field-block label{font-weight:600}
        .help{font-size:.8rem;color:#6c757d}
        .preview-chip{display:inline-flex;align-items:center;gap:.35rem;background:#f8f9fa;border:1px solid rgba(0,0,0,.06);border-radius:999px;padding:.25rem .6rem;font-size:.8rem;color:#6c757d;margin-top:.4rem}
    </style>

    <form wire:submit.prevent="save" class="row g-3">
        <div class="col-12">
            <div class="card rounded-4 shadow-sm stylish-card">
                <div class="card-header bg-light fw-bold d-flex align-items-center justify-content-between">
                    <span><i class="mdi mdi-cash-register me-1"></i> {{ __('pos.mov_title_manage') ?? 'إدارة حركة خزينة' }}</span>
                    <span class="badge bg-secondary">{{ $movement_id ? __('pos.editing') : __('pos.creating') }}</span>
                </div>

                <div class="card-body">
                    {{-- خزينة + تاريخ + نوع + مبلغ --}}
                    <div class="row g-3">
                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-safe me-1"></i> {{ __('pos.cashbox') ?? 'الخزينة' }}</label>
                            <select class="form-select" wire:model.defer="finance_settings_id">
                                <option value="">{{ __('pos.choose') ?? 'اختر...' }}</option>
                                @foreach($cashboxes as $cb)
                                    @php $name = $cb->getTranslation('name', app()->getLocale()) ?: $cb->getTranslation('name', app()->getLocale()==='ar'?'en':'ar'); @endphp
                                    <option value="{{ $cb->id }}">{{ $name }} ({{ $cb->id }})</option>
                                @endforeach
                            </select>
                            <small class="help">{{ __('pos.mov_help_cashbox') ?? 'اختر الخزينة.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $finance_settings_id ?: '—' }}</div>
                            @error('finance_settings_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-calendar me-1"></i> {{ __('pos.date') ?? 'التاريخ' }}</label>
                            <input type="date" class="form-control" wire:model.defer="movement_date">
                            <small class="help">{{ __('pos.mov_help_date') ?? 'تاريخ الحركة.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $movement_date }}</div>
                            @error('movement_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-swap-horizontal me-1"></i> {{ __('pos.direction') ?? 'النوع' }}</label>
                            <select class="form-select" wire:model.defer="direction">
                                <option value="in">{{ __('pos.mov_in') ?? 'قبض' }}</option>
                                <option value="out">{{ __('pos.mov_out') ?? 'صرف' }}</option>
                            </select>
                            <small class="help">{{ __('pos.mov_help_direction') ?? 'حدد نوع الحركة.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $direction==='in' ? __('pos.mov_in') ?? 'قبض' : __('pos.mov_out') ?? 'صرف' }}</div>
                            @error('direction') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-cash me-1"></i> {{ __('pos.amount') ?? 'المبلغ' }}</label>
                            <input type="number" step="0.01" min="0.01" class="form-control" wire:model.defer="amount" placeholder="0.00">
                            <small class="help">{{ __('pos.mov_help_amount') ?? 'أدخل المبلغ.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $amount }}</div>
                            @error('amount') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-cash-multiple me-1"></i> {{ __('pos.currency') ?? 'العملة' }}</label>
                            <input type="number" class="form-control" wire:model.defer="currency_id" placeholder="{{ __('pos.finset_ph_currency') }}">
                            <small class="help">{{ __('pos.finset_help_currency') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $currency_id ?: '—' }}</div>
                            @error('currency_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-credit-card-outline me-1"></i> {{ __('pos.method') ?? 'طريقة الدفع' }}</label>
                            <select class="form-select" wire:model.defer="method">
                                <option value="cash">{{ __('pos.method_cash') ?? 'نقدي' }}</option>
                                <option value="bank">{{ __('pos.method_bank') ?? 'بنكي' }}</option>
                                <option value="pos">{{ __('pos.method_pos') ?? 'نقطة بيع' }}</option>
                                <option value="transfer">{{ __('pos.method_transfer') ?? 'تحويل' }}</option>
                            </select>
                            <small class="help">{{ __('pos.mov_help_method') ?? 'اختر وسيلة الدفع.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $method }}</div>
                            @error('method') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="divider my-3"></div>

                    {{-- مستند/مرجع وحالة --}}
                    <div class="row g-3">
                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-numeric me-1"></i> {{ __('pos.doc_no') ?? 'رقم المستند' }}</label>
                            <input type="text" class="form-control" wire:model.defer="doc_no" placeholder="CB-0001">
                            <small class="help">{{ __('pos.mov_help_docno') ?? 'يمكن تركه فارغًا ليُملأ لاحقًا.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $doc_no ?: '—' }}</div>
                            @error('doc_no') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-link-variant me-1"></i> {{ __('pos.reference') ?? 'المرجع' }}</label>
                            <input type="text" class="form-control" wire:model.defer="reference" placeholder="#PO-123 / #INV-7">
                            <small class="help">{{ __('pos.mov_help_reference') ?? 'مرجع خارجي اختياري.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $reference ?: '—' }}</div>
                            @error('reference') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-shield-check-outline me-1"></i> {{ __('pos.status') ?? 'الحالة' }}</label>
                            <select class="form-select" wire:model.defer="status">
                                <option value="draft">{{ __('pos.draft') ?? 'مسودة' }}</option>
                                <option value="posted">{{ __('pos.posted') ?? 'مُرحّلة' }}</option>
                                <option value="void">{{ __('pos.void') ?? 'ملغاة' }}</option>
                            </select>
                            <small class="help">{{ __('pos.mov_help_status') ?? 'اختر الحالة الحالية للحركة.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $status }}</div>
                            @error('status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="divider my-3"></div>

                    {{-- ملاحظات --}}
                    <div class="row g-3">
                        <div class="col-12 field-block">
                            <label class="form-label"><i class="mdi mdi-note-text-outline me-1"></i> {{ __('pos.notes_ar') ?? 'ملاحظات (عربي)' }}</label>
                            <textarea rows="2" class="form-control" wire:model.defer="notes.ar" placeholder="{{ __('pos.finset_ph_notes_ar') }}"></textarea>
                            <small class="help">{{ __('pos.finset_help_notes') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $notes['ar'] ?: '—' }}</div>
                            @error('notes.ar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 field-block">
                            <label class="form-label"><i class="mdi mdi-note-text-outline me-1"></i> {{ __('pos.notes_en') ?? 'Notes (EN)' }}</label>
                            <textarea rows="2" class="form-control" wire:model.defer="notes.en" placeholder="{{ __('pos.finset_ph_notes_en') }}"></textarea>
                            <small class="help">{{ __('pos.finset_help_notes') }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $notes['en'] ?: '—' }}</div>
                            @error('notes.en') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex gap-2">
                    <a href="{{ route('finance.movements') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
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

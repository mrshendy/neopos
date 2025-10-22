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
                    <span><i class="mdi mdi-receipt-outline me-1"></i> {{ __('pos.receipts_title_manage') ?? 'إدارة إيصال' }}</span>
                    <span class="badge bg-secondary">{{ $receipt_id ? __('pos.editing') : __('pos.creating') }}</span>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-safe me-1"></i> {{ __('pos.cashbox') ?? 'الخزينة' }}</label>
                            <select class="form-select" wire:model.defer="finance_settings_id">
                                <option value="">{{ __('pos.choose') ?? 'اختر...' }}</option>
                                @foreach($cashboxes as $cb)
                                    @php $n=$cb->getTranslation('name',app()->getLocale()) ?: $cb->getTranslation('name',app()->getLocale()==='ar'?'en':'ar'); @endphp
                                    <option value="{{ $cb->id }}">{{ $n }} ({{ $cb->id }})</option>
                                @endforeach
                            </select>
                            <small class="help">{{ __('pos.rec_help_cashbox') ?? 'اختر الخزينة.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $finance_settings_id ?: '—' }}</div>
                            @error('finance_settings_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-calendar me-1"></i> {{ __('pos.date') ?? 'التاريخ' }}</label>
                            <input type="datetime-local" class="form-control" wire:model.defer="receipt_date">
                            <small class="help">{{ __('pos.rec_help_date') ?? 'تاريخ ووقت الإيصال.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $receipt_date }}</div>
                            @error('receipt_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-numeric me-1"></i> {{ __('pos.doc_no') ?? 'رقم' }}</label>
                            <input type="text" class="form-control" wire:model.defer="doc_no" placeholder="RCP-00001">
                            <small class="help">{{ __('pos.rec_help_docno') ?? 'اختياري؛ يمكن توليده لاحقًا.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $doc_no ?: '—' }}</div>
                            @error('doc_no') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="divider my-3"></div>

                    <div class="row g-3">
                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-credit-card-outline me-1"></i> {{ __('pos.method') ?? 'طريقة الدفع' }}</label>
                            <select class="form-select" wire:model.defer="method">
                                <option value="cash">{{ __('pos.method_cash') ?? 'نقدي' }}</option>
                                <option value="bank">{{ __('pos.method_bank') ?? 'بنكي' }}</option>
                                <option value="pos">{{ __('pos.method_pos') ?? 'نقطة بيع' }}</option>
                                <option value="transfer">{{ __('pos.method_transfer') ?? 'تحويل' }}</option>
                            </select>
                            <small class="help">{{ __('pos.rec_help_method') ?? 'اختر طريقة الدفع.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ __('pos.method_'.$method) ?? $method }}</div>
                            @error('method') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-cash me-1"></i> {{ __('pos.amount') ?? 'المبلغ' }}</label>
                            <input type="number" step="0.01" min="0" class="form-control" wire:model.defer="amount_total" placeholder="0.00">
                            <small class="help">{{ __('pos.rec_help_amount') ?? 'المبلغ الإجمالي للإيصال.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $amount_total }}</div>
                            @error('amount_total') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-undo-variant me-1"></i> {{ __('pos.return_amount') ?? 'المرتجع' }}</label>
                            <input type="number" step="0.01" min="0" class="form-control" wire:model.defer="return_amount" placeholder="0.00">
                            <small class="help">{{ __('pos.rec_help_return') ?? 'قيمة المرتجع (إن وجدت).' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $return_amount }}</div>
                            @error('return_amount') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="divider my-3"></div>

                    <div class="row g-3">
                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-link-variant me-1"></i> {{ __('pos.reference') ?? 'مرجع' }}</label>
                            <input type="text" class="form-control" wire:model.defer="reference" placeholder="#INV-123">
                            <small class="help">{{ __('pos.rec_help_reference') ?? 'مرجع خارجي اختياري.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $reference ?: '—' }}</div>
                            @error('reference') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-shield-check-outline me-1"></i> {{ __('pos.status') ?? 'الحالة' }}</label>
                            <select class="form-select" wire:model.defer="status">
                                <option value="active">{{ __('pos.active') ?? 'نشط' }}</option>
                                <option value="canceled">{{ __('pos.canceled') ?? 'ملغي' }}</option>
                            </select>
                            <small class="help">{{ __('pos.rec_help_status') ?? 'اختر حالة الإيصال.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ __('pos.'.$status) ?? $status }}</div>
                            @error('status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="divider my-3"></div>

                    <div class="row g-3">
                        <div class="col-12 field-block">
                            <label class="form-label"><i class="mdi mdi-note-text-outline me-1"></i> {{ __('pos.notes_ar') ?? 'ملاحظات (عربي)' }}</label>
                            <textarea class="form-control" rows="2" wire:model.defer="notes.ar" placeholder="{{ __('pos.finset_ph_notes_ar') }}"></textarea>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $notes['ar'] ?: '—' }}</div>
                            @error('notes.ar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 field-block">
                            <label class="form-label"><i class="mdi mdi-note-text-outline me-1"></i> {{ __('pos.notes_en') ?? 'Notes (EN)' }}</label>
                            <textarea class="form-control" rows="2" wire:model.defer="notes.en" placeholder="{{ __('pos.finset_ph_notes_en') }}"></textarea>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $notes['en'] ?: '—' }}</div>
                            @error('notes.en') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex gap-2">
                    <a href="{{ route('finance.receipts') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
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

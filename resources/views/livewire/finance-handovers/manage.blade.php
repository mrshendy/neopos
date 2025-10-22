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
                    <span><i class="mdi mdi-handshake-outline me-1"></i> {{ __('pos.handover_title_manage') ?? 'إدارة استلام/تسليم الخزينة' }}</span>
                    <span class="badge bg-secondary">{{ $handover_id ? __('pos.editing') : __('pos.creating') }}</span>
                </div>

                <div class="card-body">

                    <div class="row g-3">
                        {{-- الخزينة --}}
                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-safe me-1"></i> {{ __('pos.cashbox') ?? 'الخزينة' }}</label>
                            <select class="form-select" wire:model.defer="finance_settings_id">
                                <option value="">{{ __('pos.choose') ?? 'اختر...' }}</option>
                                @foreach($cashboxes as $cb)
                                    @php $n=$cb->getTranslation('name',app()->getLocale()) ?: $cb->getTranslation('name',app()->getLocale()==='ar'?'en':'ar'); @endphp
                                    <option value="{{ $cb->id }}">{{ $n }} ({{ $cb->id }})</option>
                                @endforeach
                            </select>
                            <small class="help">{{ __('pos.handover_help_cashbox') ?? 'اختر الخزينة محل التسليم.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $finance_settings_id ?: '—' }}</div>
                            @error('finance_settings_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- التاريخ --}}
                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-calendar me-1"></i> {{ __('pos.date') ?? 'التاريخ' }}</label>
                            <input type="datetime-local" class="form-control" wire:model.defer="handover_date">
                            <small class="help">{{ __('pos.handover_help_date') ?? 'تاريخ ووقت التسليم/الاستلام.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $handover_date }}</div>
                            @error('handover_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- رقم المستند --}}
                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-numeric me-1"></i> {{ __('pos.doc_no') ?? 'رقم' }}</label>
                            <input type="text" class="form-control" wire:model.defer="doc_no" placeholder="HND-00001">
                            <small class="help">{{ __('pos.handover_help_docno') ?? 'اختياري؛ يمكن توليده لاحقًا.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $doc_no ?: '—' }}</div>
                            @error('doc_no') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="divider my-3"></div>

                    {{-- مبالغ --}}
                    <div class="row g-3">
                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-calculator-variant-outline me-1"></i> {{ __('pos.amount_expected') ?? 'المتوقّع' }}</label>
                            <input type="number" step="0.01" min="0" class="form-control" wire:model.defer="amount_expected">
                            <small class="help">{{ __('pos.handover_help_expected') ?? 'المبلغ المتوقع حسب النظام.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $amount_expected }}</div>
                            @error('amount_expected') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-cash me-1"></i> {{ __('pos.amount_counted') ?? 'المحصّل' }}</label>
                            <input type="number" step="0.01" min="0" class="form-control" wire:model.defer="amount_counted">
                            <small class="help">{{ __('pos.handover_help_counted') ?? 'المبلغ الفعلي المُسلّم.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ $amount_counted }}</div>
                            @error('amount_counted') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-4">
                            <label class="form-label d-block"><i class="mdi mdi-scale-balance me-1"></i> {{ __('pos.difference') ?? 'الفرق' }}</label>
                            @php $diff = (float)$difference; @endphp
                            <div class="preview-chip" style="background:#fff">
                                <i class="mdi mdi-eye-outline"></i>
                                <span class="badge {{ $diff==0?'bg-success':($diff>0?'bg-primary':'bg-danger') }}">
                                    {{ number_format($difference,2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="divider my-3"></div>

                    {{-- الحالة + المستخدمين من جدول users --}}
                    <div class="row g-3">
                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-shield-check-outline me-1"></i> {{ __('pos.status') ?? 'الحالة' }}</label>
                            <select class="form-select" wire:model.defer="status">
                                <option value="draft">{{ __('pos.draft') ?? 'مسودة' }}</option>
                                <option value="submitted">{{ __('pos.submitted') ?? 'مُرسلة' }}</option>
                                <option value="received">{{ __('pos.received') ?? 'مستلمة' }}</option>
                                <option value="rejected">{{ __('pos.rejected') ?? 'مرفوضة' }}</option>
                            </select>
                            <small class="help">{{ __('pos.handover_help_status') ?? 'اختر الحالة الحالية.' }}</small>
                            <div class="preview-chip"><i class="mdi mdi-eye-outline"></i> {{ __('pos.'.$status) ?? $status }}</div>
                            @error('status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- المستخدم الذي قام بالتسليم --}}
                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-account-arrow-right-outline me-1"></i> {{ __('pos.delivered_by') ?? 'قام بالتسليم' }}</label>
                            <select class="form-select" wire:model.defer="delivered_by">
                                <option value="">{{ __('pos.choose') ?? 'اختر...' }}</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->id }})</option>
                                @endforeach
                            </select>
                            <small class="help">{{ __('pos.handover_help_delivered_by') ?? 'المستخدم الذي قام بالتسليم.' }}</small>
                            <div class="preview-chip">
                                <i class="mdi mdi-eye-outline"></i>
                                @php $dUser = $users->firstWhere('id', (int)$delivered_by); @endphp
                                {{ $dUser? $dUser->name : '—' }}
                            </div>
                            @error('delivered_by') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- المستخدم الذي استلم --}}
                        <div class="col-lg-4 field-block">
                            <label class="form-label"><i class="mdi mdi-account-arrow-left-outline me-1"></i> {{ __('pos.received_by') ?? 'المُستلم' }}</label>
                            <select class="form-select" wire:model.defer="received_by">
                                <option value="">{{ __('pos.choose') ?? 'اختر...' }}</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->id }})</option>
                                @endforeach
                            </select>
                            <small class="help">{{ __('pos.handover_help_received_by') ?? 'المستخدم الذي استلم (مطلوب عند حالة الاستلام).' }}</small>
                            <div class="preview-chip">
                                <i class="mdi mdi-eye-outline"></i>
                                @php $rUser = $users->firstWhere('id', (int)$received_by); @endphp
                                {{ $rUser? $rUser->name : '—' }}
                            </div>
                            @error('received_by') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="divider my-3"></div>

                    {{-- الملاحظات --}}
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
                    <a href="{{ route('finance.handovers') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
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

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
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-circle">
                <span>
                    {{ mb_substr($c->getTranslation('legal_name','ar') ?: $c->getTranslation('legal_name','en'), 0, 1) }}
                </span>
            </div>
            <div>
                <h4 class="mb-1">
                    <i class="mdi mdi-account-eye-outline me-2"></i>
                    {{ __('pos.title_customers_show') ?? 'عرض عميل' }}
                </h4>
                <div class="d-flex flex-wrap gap-2">
                    <span class="chip">
                        <i class="mdi mdi-account-badge-outline"></i>
                        {{ $c->type }}
                    </span>
                    <span class="chip">
                        <i class="mdi mdi-storefront-outline"></i>
                        {{ $c->channel }}
                    </span>
                    <span class="chip {{ $c->account_status === 'active' ? 'chip-success' : ($c->account_status === 'inactive' ? 'chip-secondary' : 'chip-warning') }}">
                        <i class="mdi mdi-shield-check-outline"></i>
                        {{ $c->account_status }}
                    </span>
                    @if($c->code)
                        <span class="chip"><i class="mdi mdi-shield-key-outline"></i>{{ $c->code }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('customers.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') ?? 'رجوع' }}
            </a>
            <a href="{{ route('customers.edit', $c->id) }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                <i class="mdi mdi-square-edit-outline"></i> {{ __('pos.btn_edit') ?? 'تعديل' }}
            </a>
        </div>
    </div>

    {{-- Summary Card --}}
    <div class="card shadow-sm rounded-4 stylish-card mb-3">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-lg-6">
                    <h6 class="section-title"><i class="mdi mdi-card-account-details-outline me-1"></i> {{ __('pos.section_basic') ?? 'بيانات أساسية' }}</h6>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">{{ __('pos.f_legal_name_ar') ?? 'الاسم (AR)' }}</dt>
                        <dd class="col-sm-8">{{ $c->getTranslation('legal_name','ar') }}</dd>

                        <dt class="col-sm-4">{{ __('pos.f_legal_name_en') ?? 'Name (EN)' }}</dt>
                        <dd class="col-sm-8">{{ $c->getTranslation('legal_name','en') }}</dd>

                        <dt class="col-sm-4">{{ __('pos.f_phone') ?? 'هاتف' }}</dt>
                        <dd class="col-sm-8">{{ $c->phone ?: '—' }}</dd>

                        <dt class="col-sm-4">{{ __('pos.f_tax') ?? 'الرقم الضريبي' }}</dt>
                        <dd class="col-sm-8">{{ $c->tax_number ?: '—' }}</dd>
                    </dl>
                </div>

                <div class="col-lg-6">
                    <h6 class="section-title"><i class="mdi mdi-map-outline me-1"></i> {{ __('pos.section_location') ?? 'البيانات الجغرافية' }}</h6>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">{{ __('pos.f_country') ?? 'الدولة' }}</dt>
                        <dd class="col-sm-8">
                            {{ optional($c->country)->name ?? (optional($c->country)?->getTranslation('name', app()->getLocale()) ?? '—') }}
                        </dd>

                        <dt class="col-sm-4">{{ __('pos.f_governorate') ?? 'المحافظة' }}</dt>
                        <dd class="col-sm-8">
                            {{ optional($c->governorate)->name ?? (optional($c->governorate)?->getTranslation('name', app()->getLocale()) ?? '—') }}
                        </dd>

                        <dt class="col-sm-4">{{ __('pos.f_city_select') ?? 'المدينة' }}</dt>
                        <dd class="col-sm-8">
                            {{ optional($c->cityRel)->name ?? (optional($c->cityRel)?->getTranslation('name', app()->getLocale()) ?? ($c->city ?: '—')) }}
                        </dd>

                        <dt class="col-sm-4">{{ __('pos.f_area') ?? 'المنطقة' }}</dt>
                        <dd class="col-sm-8">
                            {{ optional($c->area)->name ?? (optional($c->area)?->getTranslation('name', app()->getLocale()) ?? '—') }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    {{-- Grid: Addresses + Contacts --}}
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card shadow-sm rounded-4 stylish-card h-100">
                <div class="card-header bg-light fw-bold">
                    <i class="mdi mdi-map-marker-outline me-1"></i> {{ __('pos.section_addresses') ?? 'العناوين' }}
                </div>
                <div class="card-body">
                    @forelse($c->addresses as $i => $addr)
                        <div class="d-flex align-items-start justify-content-between border rounded-3 p-2 mb-2">
                            <div>
                                <div class="fw-semibold">{{ $addr->label ?? ($addr->type ?? 'عنوان') }}</div>
                                <div class="text-muted small">
                                    {{ trim(($addr->line1 ?? '').' '.($addr->line2 ?? '')) ?: '—' }}
                                </div>
                                <div class="text-muted small">
                                    {{ $addr->city ?? '—' }}
                                    @if($addr->postal_code) • {{ $addr->postal_code }} @endif
                                </div>
                            </div>
                            @if(isset($addr->is_default) && $addr->is_default)
                                <span class="badge bg-success-subtle text-success">{{ __('pos.default') ?? 'افتراضي' }}</span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="mdi mdi-map-marker-off-outline d-block fs-3 mb-2"></i>
                            {{ __('pos.no_addresses') ?? 'لا توجد عناوين مسجلة.' }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm rounded-4 stylish-card h-100">
                <div class="card-header bg-light fw-bold">
                    <i class="mdi mdi-account-multiple-outline me-1"></i> {{ __('pos.section_contacts') ?? 'جهات الاتصال' }}
                </div>
                <div class="card-body">
                    @forelse($c->contacts as $ct)
                        <div class="d-flex align-items-start justify-content-between border rounded-3 p-2 mb-2">
                            <div>
                                <div class="fw-semibold">{{ $ct->name ?? '—' }}</div>
                                <div class="text-muted small">
                                    {{ $ct->role ?? '—' }} @if($ct->department) • {{ $ct->department }} @endif
                                </div>
                                <div class="text-muted small">
                                    @if($ct->phone) <i class="mdi mdi-phone me-1"></i>{{ $ct->phone }} @endif
                                    @if($ct->email) • <i class="mdi mdi-email-outline me-1"></i>{{ $ct->email }} @endif
                                </div>
                            </div>
                            @if(isset($ct->is_primary) && $ct->is_primary)
                                <span class="badge bg-primary-subtle text-primary">{{ __('pos.primary') ?? 'رئيسي' }}</span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="mdi mdi-account-off-outline d-block fs-3 mb-2"></i>
                            {{ __('pos.no_contacts') ?? 'لا توجد جهات اتصال.' }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Financial & Ownership --}}
    <div class="row g-3 mt-0">
        <div class="col-lg-6">
            <div class="card shadow-sm rounded-4 stylish-card h-100">
                <div class="card-header bg-light fw-bold">
                    <i class="mdi mdi-finance me-1"></i> {{ __('pos.section_financial') ?? 'البيانات المالية' }}
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="metric">
                                <div class="label">{{ __('pos.f_credit_limit') ?? 'حد ائتماني' }}</div>
                                <div class="value">{{ is_null($c->credit_limit) ? '—' : number_format((float)$c->credit_limit, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="metric">
                                <div class="label">{{ __('pos.balance') ?? 'الرصيد' }}</div>
                                <div class="value">{{ isset($c->balance) ? number_format((float)$c->balance, 2) : '—' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="metric">
                                <div class="label">{{ __('pos.credit_status') ?? 'حالة الائتمان' }}</div>
                                <div class="value">{{ $c->credit_status ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="metric">
                                <div class="label">{{ __('pos.price_category') ?? 'فئة السعر' }}</div>
                                <div class="value">
                                    {{ optional($c->pricing?->first())->category_name ?? '—' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <dl class="row mb-0">
                        <dt class="col-sm-4">{{ __('pos.sales_rep') ?? 'مندوب المبيعات' }}</dt>
                        <dd class="col-sm-8">{{ optional($c->salesRep)->name ?? '—' }}</dd>

                        <dt class="col-sm-4">{{ __('pos.customer_group') ?? 'مجموعة العملاء' }}</dt>
                        <dd class="col-sm-8">
                            {{ optional($c->group)->name ?? (optional($c->group)?->getTranslation('name', app()->getLocale()) ?? '—') }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm rounded-4 stylish-card h-100">
                <div class="card-header bg-light fw-bold">
                    <i class="mdi mdi-file-document-outline me-1"></i> {{ __('pos.section_documents') ?? 'المستندات' }}
                </div>
                <div class="card-body">
                    @if($c->documents->count())
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('pos.doc_name') ?? 'المستند' }}</th>
                                    <th>{{ __('pos.doc_type') ?? 'النوع' }}</th>
                                    <th>{{ __('pos.status') ?? 'الحالة' }}</th>
                                    <th>{{ __('pos.date') ?? 'التاريخ' }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($c->documents as $i => $d)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td>{{ $d->name ?? '—' }}</td>
                                        <td>{{ $d->type ?? '—' }}</td>
                                        <td>{{ $d->status ?? '—' }}</td>
                                        <td>{{ optional($d->created_at)?->format('Y-m-d') ?? '—' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="mdi mdi-file-document-off-outline d-block fs-3 mb-2"></i>
                            {{ __('pos.no_documents') ?? 'لا توجد مستندات.' }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Approvals & Transactions --}}
    <div class="row g-3 mt-0">
        <div class="col-lg-6">
            <div class="card shadow-sm rounded-4 stylish-card h-100">
                <div class="card-header bg-light fw-bold">
                    <i class="mdi mdi-check-decagram-outline me-1"></i> {{ __('pos.section_approvals') ?? 'الموافقات' }}
                </div>
                <div class="card-body">
                    @if($c->approvals->count())
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('pos.stage') ?? 'المرحلة' }}</th>
                                    <th>{{ __('pos.status') ?? 'الحالة' }}</th>
                                    <th>{{ __('pos.by') ?? 'بواسطة' }}</th>
                                    <th>{{ __('pos.date') ?? 'التاريخ' }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($c->approvals as $i => $a)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td>{{ $a->stage ?? '—' }}</td>
                                        <td>{{ $a->status ?? '—' }}</td>
                                        <td>{{ optional($a->user)->name ?? '—' }}</td>
                                        <td>{{ optional($a->created_at)?->format('Y-m-d') ?? '—' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="mdi mdi-check-decagram-off-outline d-block fs-3 mb-2"></i>
                            {{ __('pos.no_approvals') ?? 'لا توجد موافقات.' }}
                        </div>
                    @endif
                </div>
                <div class="card-footer small text-muted d-flex justify-content-between">
                    <div>
                        {{ __('pos.created_by') ?? 'أنشأه' }}:
                        {{ optional($c->creator)->name ?? '—' }}
                        @if($c->created_at) • {{ $c->created_at->format('Y-m-d H:i') }} @endif
                    </div>
                    <div>
                        {{ __('pos.updated_by') ?? 'آخر تعديل' }}:
                        {{ optional($c->updater)->name ?? '—' }}
                        @if($c->updated_at) • {{ $c->updated_at->format('Y-m-d H:i') }} @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm rounded-4 stylish-card h-100">
                <div class="card-header bg-light fw-bold">
                    <i class="mdi mdi-swap-horizontal me-1"></i> {{ __('pos.section_transactions') ?? 'الحركات' }}
                </div>
                <div class="card-body">
                    @if($c->transactions->count())
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('pos.date') ?? 'التاريخ' }}</th>
                                    <th>{{ __('pos.reference') ?? 'المرجع' }}</th>
                                    <th>{{ __('pos.type') ?? 'النوع' }}</th>
                                    <th class="text-end">{{ __('pos.amount') ?? 'المبلغ' }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($c->transactions->take(10) as $i => $t)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td>{{ optional($t->date ?? $t->created_at)?->format('Y-m-d') ?? '—' }}</td>
                                        <td>{{ $t->reference ?? $t->ref_no ?? '—' }}</td>
                                        <td>{{ $t->type ?? '—' }}</td>
                                        <td class="text-end">{{ isset($t->amount) ? number_format((float)$t->amount,2) : '—' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($c->transactions->count() > 10)
                            <div class="text-muted small mt-2">
                                <i class="mdi mdi-dots-horizontal"></i> {{ __('pos.more_records') ?? 'هناك سجلات إضافية...' }}
                            </div>
                        @endif
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="mdi mdi-swap-horizontal d-block fs-3 mb-2"></i>
                            {{ __('pos.no_transactions') ?? 'لا توجد حركات.' }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Footer Actions --}}
    <div class="d-flex gap-2 justify-content-end mt-3">
        <a href="{{ route('customers.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
            <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') ?? 'رجوع' }}
        </a>
        <a href="{{ route('customers.edit', $c->id) }}" class="btn btn-success rounded-pill px-4 shadow-sm">
            <i class="mdi mdi-square-edit-outline"></i> {{ __('pos.btn_edit') ?? 'تعديل' }}
        </a>
    </div>
</div>

{{-- Minimal styling aligned with your UI --}}
<style>
    .stylish-card{border:1px solid rgba(0,0,0,.06)}
    .avatar-circle{
        width:56px;height:56px;border-radius:50%;
        display:grid;place-items:center;
        background:linear-gradient(145deg,#f3f4f6,#ffffff);
        border:1px solid rgba(0,0,0,.06);
        box-shadow:0 1px 2px rgba(16,24,40,.06);
        font-weight:700;color:#334155
    }
    .chip{
        display:inline-flex;align-items:center;gap:.35rem;
        padding:.25rem .6rem;border-radius:999px;font-size:.8rem;
        background:#f8f9fa;border:1px solid rgba(0,0,0,.06);color:#334155
    }
    .chip-success{background:#e9f9ee;border-color:#b7ebc0;color:#117a37}
    .chip-secondary{background:#f1f5f9;border-color:#e2e8f0;color:#475569}
    .chip-warning{background:#fff7e6;border-color:#ffe7ba;color:#ad6800}
    .section-title{font-weight:700;color:#6c757d;letter-spacing:.3px}
    .metric{
        padding:.9rem;border:1px solid rgba(0,0,0,.06);
        border-radius:.9rem;background:#fff;height:100%
    }
    .metric .label{font-size:.8rem;color:#6c757d}
    .metric .value{font-weight:700;font-size:1.05rem}
    .divider{height:1px;background:linear-gradient(90deg,rgba(0,0,0,.04),rgba(0,0,0,.12),rgba(0,0,0,.04));margin:1rem 0}
</style>

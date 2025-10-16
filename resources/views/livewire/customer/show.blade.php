<div class="row g-3">

    {{-- ✅ بطاقة الحالة العامة --}}
    <div class="col-12">
        <div class="alert {{ $this->readyToBuy ? 'alert-success' : 'alert-warning' }} rounded-3 shadow-sm d-flex align-products-center gap-2">
            <i class="mdi {{ $this->readyToBuy ? 'mdi-check-decagram' : 'mdi-alert' }} fs-4"></i>
            <span class="fw-semibold">
                {{ $this->readyToBuy ? 'Ready to Buy ✅' : 'غير جاهز للشراء — تأكد من العقد/الوثائق/الحظر' }}
            </span>
        </div>
    </div>

    {{-- ✅ بيانات أساسية --}}
    <div class="col-lg-6">
        <div class="card shadow-sm rounded-4 h-100">
            <div class="card-header bg-light fw-bold">
                <i class="mdi mdi-card-account-details-outline"></i> {{ __('pos.supplier_title') }}
            </div>
            <div class="card-body">
                <dl class="row mb-0 small">
                    <dt class="col-sm-4">{{ __('pos.supplier_code') }}</dt>
                    <dd class="col-sm-8 fw-semibold">{{ $supplier->code }}</dd>

                    <dt class="col-sm-4">{{ __('pos.supplier_name') }}</dt>
                    <dd class="col-sm-8">
                        {{ $supplier->getTranslation('name', app()->getLocale()) }}
                    </dd>

                    <dt class="col-sm-4">{{ __('pos.category') }}</dt>
                    <dd class="col-sm-8">
                        {{ optional($supplier->category)?->getTranslation('name', app()->getLocale()) ?? '-' }}
                    </dd>

                    <dt class="col-sm-4">{{ __('pos.payment_term') }}</dt>
                    <dd class="col-sm-8">
                        {{ optional($supplier->paymentTerm)?->getTranslation('name', app()->getLocale()) ?? '-' }}
                    </dd>

                    <dt class="col-sm-4">{{ __('pos.commercial_register') }}</dt>
                    <dd class="col-sm-8">{{ $supplier->commercial_register ?? '-' }}</dd>

                    <dt class="col-sm-4">{{ __('pos.tax_number') }}</dt>
                    <dd class="col-sm-8">{{ $supplier->tax_number ?? '-' }}</dd>

                    <dt class="col-sm-4">{{ __('pos.status') }}</dt>
                    <dd class="col-sm-8">
                        <span class="badge rounded-pill {{ $supplier->status=='active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $supplier->status=='active' ? __('pos.status_active') : __('pos.status_inactive') }}
                        </span>
                    </dd>
                </dl>
            </div>
        </div>
    </div>

    {{-- ✅ الموقع الجغرافي --}}
    <div class="col-lg-6">
        <div class="card shadow-sm rounded-4 h-100">
            <div class="card-header bg-light fw-bold">
                <i class="mdi mdi-map-marker-radius"></i>
                {{ __('pos.country') }} / {{ __('pos.governorate') }} / {{ __('pos.city') }} / {{ __('pos.area') }}
            </div>
            <div class="card-body">
                <dl class="row mb-0 small">
                    <dt class="col-sm-4">{{ __('pos.country') }}</dt>
                    <dd class="col-sm-8">{{ optional($supplier->country)->name ?? '-' }}</dd>

                    <dt class="col-sm-4">{{ __('pos.governorate') }}</dt>
                    <dd class="col-sm-8">{{ optional($supplier->governorate)->name ?? '-' }}</dd>

                    <dt class="col-sm-4">{{ __('pos.city') }}</dt>
                    <dd class="col-sm-8">{{ optional($supplier->city)->name ?? '-' }}</dd>

                    <dt class="col-sm-4">{{ __('pos.area') }}</dt>
                    <dd class="col-sm-8">{{ optional($supplier->area)->name ?? '-' }}</dd>
                </dl>
            </div>
        </div>
    </div>

    {{-- ✅ عناوين التسليم --}}
    <div class="col-12">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-light fw-bold">
                <i class="mdi mdi-map"></i> {{ __('pos.supplier_title') }} — عناوين التسليم
            </div>
            <div class="card-body">
                @forelse($supplier->addresses as $addr)
                    <div class="border rounded-3 p-3 mb-2">
                        <div class="d-flex justify-content-between align-products-center">
                            <strong>
                                {{ $addr->getTranslation('label', app()->getLocale()) ?? '-' }}
                                @if($addr->is_default)
                                    <span class="badge bg-info ms-1">Default</span>
                                @endif
                            </strong>
                            <small class="text-muted">#{{ $addr->id }}</small>
                        </div>
                        <div class="text-muted mt-1">
                            {{ $addr->getTranslation('address_line', app()->getLocale()) ?? '-' }}
                        </div>
                        <div class="small mt-1">
                            {{ optional($addr->country)->name ?? '-' }}
                            — {{ optional($addr->governorate)->name ?? '-' }}
                            — {{ optional($addr->city)->name ?? '-' }}
                            — {{ optional($addr->area)->name ?? '-' }}
                            @if($addr->postal_code) • {{ $addr->postal_code }} @endif
                        </div>
                    </div>
                @empty
                    <div class="text-muted">{{ __('pos.no_data') }}</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ✅ جهات الاتصال --}}
    <div class="col-12">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-light fw-bold">
                <i class="mdi mdi-account-multiple-outline"></i> جهات الاتصال
            </div>
            <div class="card-body">
                @forelse($supplier->contacts as $c)
                    <div class="border rounded-3 p-3 mb-2">
                        <div class="d-flex justify-content-between align-products-center">
                            <strong>{{ $c->getTranslation('name', app()->getLocale()) }}</strong>
                            @if($c->is_primary)<span class="badge bg-primary">Primary</span>@endif
                        </div>
                        <div class="small text-muted">
                            {{ $c->getTranslation('role', app()->getLocale()) ?? '-' }}
                            • {{ $c->phone ?? '-' }} • {{ $c->email ?? '-' }}
                        </div>
                    </div>
                @empty
                    <div class="text-muted">{{ __('pos.no_data') }}</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ✅ وثائق الجودة --}}
    <div class="col-12">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-light fw-bold">
                <i class="mdi mdi-file-check-outline"></i> وثائق الجودة
            </div>
            <div class="card-body table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>النوع</th>
                            <th>الرقم</th>
                            <th>تاريخ الإصدار</th>
                            <th>تاريخ الانتهاء</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supplier->qualityDocs as $d)
                            <tr>
                                <td>{{ optional($d->type)?->getTranslation('name', app()->getLocale()) ?? '-' }}</td>
                                <td>{{ $d->number ?? '-' }}</td>
                                <td>{{ $d->issue_date?->format('Y-m-d') ?? '-' }}</td>
                                <td>{{ $d->expiry_date?->format('Y-m-d') ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $d->status=='valid'?'bg-success':'bg-danger' }}">
                                        {{ $d->status=='valid' ? 'سارية' : 'منتهية' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-muted">{{ __('pos.no_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ✅ العقود وبنود الأسعار --}}
    <div class="col-12">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-light fw-bold">
                <i class="mdi mdi-file-document-outline"></i> العقود
            </div>
            <div class="card-body">
                @forelse($supplier->contracts as $c)
                    <div class="border rounded-3 p-3 mb-3">
                        <div class="d-flex justify-content-between align-products-center">
                            <div>
                                <strong>
                                    {{ $c->start_date?->format('Y-m-d') ?? '—' }} → {{ $c->end_date?->format('Y-m-d') ?? '—' }}
                                </strong>
                                <span class="badge ms-2 {{ $c->status=='active'?'bg-success':'bg-secondary' }}">{{ $c->status }}</span>
                            </div>
                            <div class="small text-muted">
                                {{ __('pos.payment_term') }}:
                                {{ optional($c->paymentTerm)?->getTranslation('name', app()->getLocale()) ?? '-' }}
                            </div>
                        </div>

                        @if($c->products->count())
                            <div class="table-responsive mt-2">
                                <table class="table table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>SKU</th>
                                            <th>المنتج</th>
                                            <th>السعر</th>
                                            <th>min</th>
                                            <th>max</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($c->products as $it)
                                            <tr>
                                                <td>{{ $it->product_sku ?? '-' }}</td>
                                                <td>{{ $it->getTranslation('product_name', app()->getLocale()) ?? '-' }}</td>
                                                <td>{{ number_format($it->price, 4) }}</td>
                                                <td>{{ $it->min_qty ?? '-' }}</td>
                                                <td>{{ $it->max_qty ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-muted">{{ __('pos.no_data') }}</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ✅ الخصومات / الحظر --}}
    <div class="col-lg-6">
        <div class="card shadow-sm rounded-4 h-100">
            <div class="card-header bg-light fw-bold"><i class="mdi mdi-sale"></i> الخصومات</div>
            <div class="card-body">
                @forelse($supplier->discounts as $d)
                    <div class="small mb-2">
                        [{{ $d->type }}] — %
                        {{ $d->percentage ?? '-' }} | {{ $d->amount ?? '-' }} |
                        {{ $d->from_qty ?? 0 }}–{{ $d->to_qty ?? '∞' }}
                        <span class="badge {{ $d->status=='active'?'bg-success':'bg-secondary' }} ms-1">{{ $d->status }}</span>
                    </div>
                @empty
                    <div class="text-muted">{{ __('pos.no_data') }}</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm rounded-4 h-100">
            <div class="card-header bg-light fw-bold"><i class="mdi mdi-block-helper"></i> الحظر</div>
            <div class="card-body">
                @forelse($supplier->blacklists as $b)
                    <div class="small mb-2">
                        {{ $b->start_date?->format('Y-m-d') ?? '—' }} → {{ $b->end_date?->format('Y-m-d') ?? 'مفتوح' }}
                        <div class="text-muted">{{ $b->reason ?? '-' }}</div>
                    </div>
                @empty
                    <div class="text-muted">{{ __('pos.no_data') }}</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ✅ التقييم --}}
    <div class="col-12">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-light fw-bold"><i class="mdi mdi-clipboard-check-outline"></i> التقييم</div>
            <div class="card-body">
                @forelse($supplier->evaluations as $e)
                    <div class="border rounded-3 p-3 mb-2">
                        <div class="d-flex justify-content-between align-products-center">
                            <strong>{{ $e->period_start?->format('Y-m-d') ?? '—' }} → {{ $e->period_end?->format('Y-m-d') ?? '—' }}</strong>
                            <span class="badge bg-info">Total: {{ $e->total_score }}</span>
                        </div>
                        @if($e->scores->count())
                            <ul class="small mt-2 mb-0">
                                @foreach($e->scores as $s)
                                    <li>
                                        {{ optional($s->criterion)?->getTranslation('name', app()->getLocale()) ?? '-' }}
                                        — {{ $s->score }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @empty
                    <div class="text-muted">{{ __('pos.no_data') }}</div>
                @endforelse
            </div>
        </div>
    </div>

</div>

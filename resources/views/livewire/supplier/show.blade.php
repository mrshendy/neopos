@php($loc = app()->getLocale())
<div class="row g-3">

    {{-- بطاقة الحالة العامة --}}
    <div class="col-12">
        <div class="alert {{ $this->readyToBuy ? 'alert-success' : 'alert-warning' }} rounded-3 shadow-sm">
            <i class="mdi {{ $this->readyToBuy ? 'mdi-check-decagram' : 'mdi-alert' }}"></i>
            {{ $this->readyToBuy ? 'Ready to Buy ✅' : 'غير جاهز للشراء — تأكد من العقد/الوثائق/الحظر' }}
        </div>
    </div>

    {{-- بيانات أساسية --}}
    <div class="col-lg-6">
        <div class="card shadow-sm rounded-4 h-100">
            <div class="card-header bg-light fw-bold">
                <i class="mdi mdi-card-account-details-outline"></i> {{ __('pos.supplier_title') }}
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">{{ __('pos.supplier_code') }}</dt>
                    <dd class="col-sm-8">{{ $supplier->code }}</dd>

                    <dt class="col-sm-4">{{ __('pos.supplier_name') }}</dt>
                    <dd class="col-sm-8">
                        {{ $supplier->getTranslation('name', $loc) ?? '-' }}
                    </dd>

                    <dt class="col-sm-4">{{ __('pos.category') }}</dt>
                    <dd class="col-sm-8">
                        {{ optional($supplier->category)?->getTranslation('name', $loc) ?? '-' }}
                    </dd>

                    <dt class="col-sm-4">{{ __('pos.payment_term') }}</dt>
                    <dd class="col-sm-8">
                        {{ optional($supplier->paymentTerm)?->getTranslation('name', $loc) ?? '-' }}
                    </dd>

                    <dt class="col-sm-4">{{ __('pos.commercial_register') }}</dt>
                    <dd class="col-sm-8">{{ $supplier->commercial_register ?? '-' }}</dd>

                    <dt class="col-sm-4">{{ __('pos.tax_number') }}</dt>
                    <dd class="col-sm-8">{{ $supplier->tax_number ?? '-' }}</dd>

                    <dt class="col-sm-4">{{ __('pos.status') }}</dt>
                    <dd class="col-sm-8">
                        <span class="badge {{ $supplier->status=='active'?'bg-success':'bg-secondary' }}">
                            {{ $supplier->status=='active' ? __('pos.status_active') : __('pos.status_inactive') }}
                        </span>
                    </dd>
                </dl>
            </div>
        </div>
    </div>

    {{-- الموقع الجغرافي --}}
    <div class="col-lg-6">
        <div class="card shadow-sm rounded-4 h-100">
            <div class="card-header bg-light fw-bold">
                <i class="mdi mdi-map-marker-radius"></i>
                {{ __('pos.country') }} / {{ __('pos.governorate') }} / {{ __('pos.city') }} / {{ __('pos.area') }}
            </div>
            <div class="card-body">
                <dl class="row mb-0">
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

    {{-- عناوين التسليم --}}
    <div class="col-12">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-light fw-bold"><i class="mdi mdi-map"></i> عناوين المورد</div>
            <div class="card-body">
                @forelse($supplier->addresses ?? [] as $addr)
                    <div class="border rounded-3 p-3 mb-2">
                        <div class="d-flex justify-content-between">
                            <strong>
                                {{ $addr->getTranslation('label', $loc) ?? '-' }}
                                @if($addr->is_default) <span class="badge bg-info">Default</span> @endif
                            </strong>
                            <small class="text-muted">#{{ $addr->id }}</small>
                        </div>
                        <div class="text-muted">
                            {{ $addr->getTranslation('address_line', $loc) ?? '-' }}
                        </div>
                        <div class="small mt-1">
                            {{ optional($addr->country)->name ?? '-' }} -
                            {{ optional($addr->governorate)->name ?? '-' }} -
                            {{ optional($addr->city)->name ?? '-' }} -
                            {{ optional($addr->area)->name ?? '-' }}
                            @if($addr->postal_code) • {{ $addr->postal_code }} @endif
                        </div>
                    </div>
                @empty
                    <div class="text-muted">{{ __('pos.no_data') }}</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- جهات الاتصال --}}
    <div class="col-12">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-light fw-bold"><i class="mdi mdi-account-multiple-outline"></i> جهات الاتصال</div>
            <div class="card-body">
                @forelse($supplier->contacts ?? [] as $c)
                    <div class="border rounded-3 p-3 mb-2">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $c->getTranslation('name', $loc) ?? '-' }}</strong>
                            @if($c->is_primary)<span class="badge bg-primary">Primary</span>@endif
                        </div>
                        <div class="small text-muted">
                            {{ $c->getTranslation('role', $loc) ?? '-' }}
                            • {{ $c->phone ?? '-' }} • {{ $c->email ?? '-' }}
                        </div>
                    </div>
                @empty
                    <div class="text-muted">{{ __('pos.no_data') }}</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- وثائق الجودة --}}
    <div class="col-12">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-light fw-bold"><i class="mdi mdi-file-check-outline"></i> وثائق الجودة</div>
            <div class="card-body table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>النوع</th><th>الرقم</th><th>تاريخ الإصدار</th><th>تاريخ الانتهاء</th><th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supplier->qualityDocs ?? [] as $d)
                            <tr>
                                <td>{{ optional($d->type)?->getTranslation('name', $loc) ?? '-' }}</td>
                                <td>{{ $d->number ?? '-' }}</td>
                                <td>{{ optional($d->issue_date)?->format('Y-m-d') ?? '-' }}</td>
                                <td>{{ optional($d->expiry_date)?->format('Y-m-d') ?? '-' }}</td>
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

    {{-- العقود وبنود الأسعار --}}
    <div class="col-12">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-light fw-bold"><i class="mdi mdi-file-document-outline"></i> العقود</div>
            <div class="card-body">
                @forelse($supplier->contracts ?? [] as $c)
                    <div class="border rounded-3 p-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>
                                    من {{ optional($c->start_date)?->format('Y-m-d') ?? '-' }}
                                    إلى {{ optional($c->end_date)?->format('Y-m-d') ?? '-' }}
                                </strong>
                                <span class="badge {{ $c->status=='active'?'bg-success':'bg-secondary' }}">{{ $c->status }}</span>
                            </div>
                            <div class="small text-muted">
                                {{ __('pos.payment_term') }}:
                                {{ optional($c->paymentTerm)?->getTranslation('name', $loc) ?? '-' }}
                            </div>
                        </div>

                        @if(($c->items ?? collect())->count())
                            <div class="table-responsive mt-2">
                                <table class="table table-sm">
                                    <thead>
                                        <tr><th>SKU</th><th>المنتج</th><th>السعر</th><th>min</th><th>max</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach($c->items as $it)
                                            <tr>
                                                <td>{{ $it->product_sku ?? '-' }}</td>
                                                <td>{{ $it->getTranslation('product_name', $loc) ?? '-' }}</td>
                                                <td>{{ isset($it->price) ? number_format($it->price, 4) : '-' }}</td>
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

    {{-- الخصومات --}}
    <div class="col-lg-6">
        <div class="card shadow-sm rounded-4 h-100">
            <div class="card-header bg-light fw-bold"><i class="mdi mdi-sale"></i> الخصومات</div>
            <div class="card-body">
                @forelse($supplier->discounts ?? [] as $d)
                    <div class="small mb-2">
                        [{{ $d->type ?? '-' }}] % {{ $d->percentage ?? '-' }} | {{ $d->amount ?? '-' }} |
                        {{ $d->from_qty ?? 0 }}–{{ $d->to_qty ?? '∞' }}
                        <span class="badge {{ $d->status=='active'?'bg-success':'bg-secondary' }}">{{ $d->status ?? '-' }}</span>
                    </div>
                @empty
                    <div class="text-muted">{{ __('pos.no_data') }}</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- الحظر --}}
    <div class="col-lg-6">
        <div class="card shadow-sm rounded-4 h-100">
            <div class="card-header bg-light fw-bold"><i class="mdi mdi-block-helper"></i> الحظر</div>
            <div class="card-body">
                @forelse($supplier->blacklists ?? [] as $b)
                    <div class="small mb-2">
                        {{ optional($b->start_date)?->format('Y-m-d') ?? '-' }} →
                        {{ optional($b->end_date)?->format('Y-m-d') ?? 'مفتوح' }}
                        <div class="text-muted">{{ $b->reason ?? '-' }}</div>
                    </div>
                @empty
                    <div class="text-muted">{{ __('pos.no_data') }}</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- التقييم --}}
    <div class="col-12">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-light fw-bold"><i class="mdi mdi-clipboard-check-outline"></i> التقييم</div>
            <div class="card-body">
                @forelse($supplier->evaluations ?? [] as $e)
                    <div class="border rounded-3 p-3 mb-2">
                        <div class="d-flex justify-content-between">
                            <strong>
                                {{ optional($e->period_start)?->format('Y-m-d') ?? '-' }} →
                                {{ optional($e->period_end)?->format('Y-m-d') ?? '-' }}
                            </strong>
                            <span class="badge bg-info">Total: {{ $e->total_score ?? 0 }}</span>
                        </div>
                        @if(($e->scores ?? collect())->count())
                            <ul class="small mt-2 mb-0">
                                @foreach($e->scores as $s)
                                    <li>
                                        {{ optional($s->criterion)?->getTranslation('name', $loc) ?? '-' }}
                                        — {{ $s->score ?? '-' }}
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

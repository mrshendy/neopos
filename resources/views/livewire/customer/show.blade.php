<div class="page-wrap">

    {{-- alerts (اختياري) --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="mdi mdi-account-outline me-2"></i> {{ __('pos.title_customers_show') ?? 'بيانات العميل' }}
            </h4>
            <div class="text-muted small">{{ __('pos.subtitle_customers_show') ?? 'عرض كل التفاصيل' }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="mdi mdi-pencil"></i> {{ __('pos.btn_edit') }}
            </a>
            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') }}
            </a>
        </div>
    </div>

    {{-- basic info --}}
    <div class="card shadow-sm rounded-4 mb-3">
        <div class="card-body">
            <h6 class="text-muted fw-bold mb-3">
                <i class="mdi mdi-card-account-details-outline me-1"></i> {{ __('pos.section_basic') }}
            </h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="mini-field">
                        <div class="label"><i class="mdi mdi-shield-key-outline me-1"></i>{{ __('pos.f_code') }}</div>
                        <div class="value font-monospace">{{ $customer->code ?: '—' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mini-field">
                        <div class="label"><i class="mdi mdi-alphabetical me-1"></i>{{ __('pos.f_legal_name_ar') }}</div>
                        <div class="value">{{ $customer->getTranslation('legal_name','ar') ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mini-field">
                        <div class="label"><i class="mdi mdi-alphabetical-variant me-1"></i>{{ __('pos.f_legal_name_en') }}</div>
                        <div class="value">{{ $customer->getTranslation('legal_name','en') ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mini-field">
                        <div class="label"><i class="mdi mdi-account-badge-outline me-1"></i>{{ __('pos.f_type') }}</div>
                        <div class="value">{{ $customer->type ?: '—' }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mini-field">
                        <div class="label"><i class="mdi mdi-storefront-outline me-1"></i>{{ __('pos.f_channel') }}</div>
                        <div class="value">
                            {{ __(
                                $customer->channel==='retail'?'pos.opt_retail':
                                ($customer->channel==='wholesale'?'pos.opt_wholesale':
                                ($customer->channel==='online'?'pos.opt_online':'pos.opt_pharmacy'))
                            ) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- location --}}
    <div class="card shadow-sm rounded-4 mb-3">
        <div class="card-body">
            <h6 class="text-muted fw-bold mb-3">
                <i class="mdi mdi-map-outline me-1"></i> {{ __('pos.section_location') }}
            </h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="mini-field">
                        <div class="label"><i class="mdi mdi-earth me-1"></i>{{ __('pos.f_country') }}</div>
                        <div class="value">
                            @php
                                $cn = $customer->country ?? null;
                                $cnName = $cn ? (method_exists($cn,'getTranslation') ? $cn->getTranslation('name', app()->getLocale()) : ($cn->name ?? '')) : null;
                            @endphp
                            {{ $cnName ?: '—' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mini-field">
                        <div class="label"><i class="mdi mdi-map me-1"></i>{{ __('pos.f_governorate') }}</div>
                        <div class="value">
                            @php
                                $go = $customer->governorate ?? null;
                                $goName = $go ? (method_exists($go,'getTranslation') ? $go->getTranslation('name', app()->getLocale()) : ($go->name ?? '')) : null;
                            @endphp
                            {{ $goName ?: '—' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mini-field">
                        <div class="label"><i class="mdi mdi-city-variant-outline me-1"></i>{{ __('pos.f_city_select') }}</div>
                        <div class="value">{{ optional($customer->cityRel)->name ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mini-field">
                        <div class="label"><i class="mdi mdi-map-marker-radius-outline me-1"></i>{{ __('pos.f_area') }}</div>
                        <div class="value">{{ optional($customer->area)->name ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mini-field">
                        <div class="label"><i class="mdi mdi-form-textbox me-1"></i>{{ __('pos.f_city_text') }}</div>
                        <div class="value">{{ $customer->city ?: '—' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- contact & finance --}}
    <div class="card shadow-sm rounded-4">
        <div class="card-body">
            <h6 class="text-muted fw-bold mb-3">
                <i class="mdi mdi-phone-outline me-1"></i> {{ __('pos.section_contact_finance') }}
            </h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="mini-field">
                        <div class="label"><i class="mdi mdi-phone-outline me-1"></i>{{ __('pos.f_phone') }}</div>
                        <div class="value">{{ $customer->phone ?: '—' }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mini-field">
                        <div class="label"><i class="mdi mdi-receipt-text-outline me-1"></i>{{ __('pos.f_tax') }}</div>
                        <div class="value">{{ $customer->tax_number ?: '—' }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mini-field">
                        <div class="label"><i class="mdi mdi-credit-card-outline me-1"></i>{{ __('pos.f_credit_limit') }}</div>
                        <div class="value">{{ strlen((string)$customer->credit_limit) ? $customer->credit_limit : '—' }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mini-field">
                        <div class="label"><i class="mdi mdi-shield-check-outline me-1"></i>{{ __('pos.f_account_status') }}</div>
                        <div class="value">
                            {{
                                __(
                                    $customer->account_status==='active'?'pos.status_active':
                                    ($customer->account_status==='inactive'?'pos.status_inactive':'pos.status_suspended')
                                )
                            }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .mini-field .label{
        font-size:.8rem; color:#6b7280; margin-bottom:.2rem; display:flex; align-items:center; gap:.35rem;
    }
    .mini-field .value{
        background:#fff; border:1px solid rgba(0,0,0,.06); border-radius:.65rem; padding:.5rem .75rem;
        box-shadow:0 1px 2px rgba(16,24,40,.04);
    }
</style>

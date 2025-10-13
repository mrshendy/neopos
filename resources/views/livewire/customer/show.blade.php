<div>
  {{-- Header --}}
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">
      <i class="mdi mdi-account-details-outline me-2"></i> {{ __('pos.title_customers_show') }}
    </h4>

    <div class="d-flex gap-2">
      <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-success rounded-pill px-4 shadow-sm">
        <i class="mdi mdi-pencil-outline me-1"></i> {{ __('pos.btn_edit') }}
      </a>
      <a href="{{ route('customers.index') }}" class="btn btn-secondary rounded-pill px-4 shadow-sm">
        <i class="mdi mdi-arrow-left me-1"></i> {{ __('pos.btn_back') }}
      </a>
    </div>
  </div>

  <div class="row g-3">
    {{-- الملف العام --}}
    <div class="col-lg-4">
      <div class="card border-0 shadow-lg rounded-4 h-100">
        <div class="card-header bg-light fw-bold d-flex align-items-center justify-content-between">
          <span><i class="mdi mdi-card-account-details-outline me-2"></i> {{ __('pos.card_profile') }}</span>

          {{-- شارة الحالة --}}
          @php
            $status = $customer->account_status;
            $statusColor = $status === 'active' ? 'success' : ($status === 'inactive' ? 'secondary' : 'warning text-dark');
            $statusIcon  = $status === 'active' ? 'mdi-check-decagram' : ($status === 'inactive' ? 'mdi-sleep' : 'mdi-alert-decagram');
          @endphp
          <span class="badge bg-{{ $statusColor }}">
            <i class="mdi {{ $statusIcon }} me-1"></i>
            {{ __('pos.status_'.$status) }}
          </span>
        </div>

        <div class="card-body">
          <p class="text-muted small mb-3">{{ __('pos.desc_profile') }}</p>

          {{-- أوفاتار بالأحرف الأولى --}}
          @php
            $locale = app()->getLocale();
            $displayName = $customer->getTranslation('legal_name', $locale);
            $initials = mb_substr($displayName, 0, 1);
          @endphp
          <div class="d-flex align-items-center mb-3">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                 style="width:48px;height:48px;">
              <span class="fw-bold fs-5">{{ $initials }}</span>
            </div>
            <div>
              <div class="fw-bold">{{ $displayName }}</div>
              <div class="text-muted small">
                <i class="mdi mdi-account-badge-outline me-1"></i>
                {{ __('pos.f_type') }}:
                <span class="fw-semibold">{{ $customer->type }}</span>
                <span class="text-muted mx-1">•</span>
                <i class="mdi mdi-storefront-outline me-1"></i>
                {{ __('pos.f_channel') }}:
                <span class="fw-semibold">{{ $customer->channel }}</span>
              </div>
            </div>
          </div>

          {{-- بيانات أساسية --}}
          <div class="mb-2">
            <span class="text-muted">{{ __('pos.th_code') }}:</span>
            <span class="fw-semibold user-select-all" id="custCode">{{ $customer->code }}</span>
            <button type="button" class="btn btn-sm btn-light border ms-1"
              data-bs-toggle="tooltip" title="{{ __('pos.tt_copy') }}"
              onclick="navigator.clipboard.writeText('{{ $customer->code }}')">
              <i class="mdi mdi-content-copy"></i>
            </button>
          </div>

          <div class="mb-2">
            <span class="text-muted">{{ __('pos.th_phone') }}:</span>
            <span class="fw-semibold">{{ $customer->phone ?? '-' }}</span>
          </div>

          <div class="mb-2">
            <span class="text-muted">{{ __('pos.f_tax') }}:</span>
            <span class="fw-semibold">{{ $customer->tax_number ?? '-' }}</span>
          </div>

          <hr>

          {{-- العنوان --}}
          <div class="mb-2">
            <span class="text-muted">{{ __('pos.th_country') }}:</span>
            <span class="fw-semibold">{{ optional($customer->country)->getTranslation('name', $locale) ?? '-' }}</span>
          </div>
          <div class="mb-2">
            <span class="text-muted">{{ __('pos.th_governorate') }}:</span>
            <span class="fw-semibold">{{ optional($customer->governorate)->getTranslation('name', $locale) ?? '-' }}</span>
          </div>
          <div class="mb-2">
            <span class="text-muted">{{ __('pos.th_city') }}:</span>
            <span class="fw-semibold">
              {{ $customer->city ?? optional($customer->cityRel)->name ?? '-' }}
            </span>
          </div>
          <div class="mb-2">
            <span class="text-muted">{{ __('pos.th_area') }}:</span>
            <span class="fw-semibold">{{ optional($customer->area)->name ?? '-' }}</span>
          </div>

          <div class="mb-2">
            <span class="text-muted">{{ __('pos.th_price_category') }}:</span>
            <span class="fw-semibold">{{ optional($customer->priceCategory)->getTranslation('name', $locale) ?? '-' }}</span>
          </div>

          <div class="mt-3 d-flex flex-wrap gap-2">
            <span class="badge bg-light text-dark border">
              <i class="mdi mdi-alphabetical-variant me-1"></i>
              {{ $customer->getTranslation('legal_name', 'en') }}
            </span>
            <span class="badge bg-light text-dark border">
              <i class="mdi mdi-alphabetical me-1"></i>
              {{ $customer->getTranslation('legal_name', 'ar') }}
            </span>
          </div>
        </div>
      </div>
    </div>

    {{-- الملخص المالي والمعاملات --}}
    <div class="col-lg-8">
      <div class="card border-0 shadow-lg rounded-4 h-100">
        <div class="card-header bg-light fw-bold d-flex align-items-center justify-content-between">
          <span><i class="mdi mdi-cash-multiple me-2"></i> {{ __('pos.card_summary') }}</span>
          @if($customer->credit_limit > 0)
            @php
              $usage = min(($customer->balance / max($customer->credit_limit,1))*100, 100);
            @endphp
            <span class="text-muted small">
              {{ __('pos.credit_used') }} {{ number_format($usage, 0) }}%
            </span>
          @endif
        </div>

        <div class="card-body">
          <p class="text-muted small">{{ __('pos.desc_summary') }}</p>

          {{-- KPIs --}}
          <div class="row text-center">
            <div class="col-md-3 mb-3">
              <div class="p-3 border rounded-3">
                <div class="text-muted small"><i class="mdi mdi-wallet me-1"></i> {{ __('pos.th_balance') }}</div>
                <div class="fs-5 fw-bold">{{ number_format($customer->balance, 2) }}</div>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="p-3 border rounded-3">
                <div class="text-muted small"><i class="mdi mdi-credit-card-outline me-1"></i> {{ __('pos.th_credit_limit') }}</div>
                <div class="fs-5 fw-bold">{{ number_format($customer->credit_limit, 2) }}</div>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="p-3 border rounded-3">
                <div class="text-muted small"><i class="mdi mdi-shield-check-outline me-1"></i> {{ __('pos.th_available') }}</div>
                <div class="fs-5 fw-bold">
                  {{ number_format(max($customer->credit_limit - $customer->balance, 0), 2) }}
                </div>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="p-3 border rounded-3">
                <div class="text-muted small"><i class="mdi mdi-file-document-outline me-1"></i> {{ __('pos.th_open_invoices') }}</div>
                <div class="fs-5 fw-bold">
                  {{ $customer->transactions()->where('type','invoice')->count() }}
                </div>
              </div>
            </div>
          </div>

          {{-- شريط استخدام الائتمان --}}
          @if($customer->credit_limit > 0)
            @php $bar = $usage; @endphp
            <div class="progress mb-3" style="height: 10px;">
              <div class="progress-bar {{ $bar < 70 ? 'bg-success' : ($bar < 90 ? 'bg-warning' : 'bg-danger') }}"
                   role="progressbar" style="width: {{ $bar }}%;" aria-valuenow="{{ $bar }}"
                   aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          @endif

          <hr>

          {{-- آخر المعاملات --}}
          <h6 class="fw-bold mb-2">
            <i class="mdi mdi-clock-outline me-1"></i> {{ __('pos.card_transactions') }}
          </h6>

          @php $lastTx = $customer->transactions()->latest()->limit(10)->get(); @endphp

          @if($lastTx->count())
            <div class="table-responsive">
              <table class="table table-sm table-striped align-middle">
                <thead class="table-light">
                  <tr>
                    <th>#</th>
                    <th><i class="mdi mdi-tag-outline me-1"></i>{{ __('pos.th_type') }}</th>
                    <th><i class="mdi mdi-file-document-outline me-1"></i>{{ __('pos.th_reference') }}</th>
                    <th><i class="mdi mdi-calendar-clock me-1"></i>{{ __('pos.th_date') }}</th>
                    <th class="text-end"><i class="mdi mdi-currency-usd me-1"></i>{{ __('pos.th_value') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($lastTx as $tx)
                    <tr>
                      <td>{{ $tx->id }}</td>
                      <td class="text-uppercase">{{ $tx->type }}</td>
                      <td>{{ $tx->reference_no }}</td>
                      <td>{{ \Illuminate\Support\Str::of($tx->date)->replace('T',' ') }}</td>
                      <td class="text-end fw-semibold">{{ number_format($tx->amount, 2) }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="text-center text-muted py-4">
              <i class="mdi mdi-inbox-outline fs-2 d-block mb-2"></i>
              {{ __('pos.no_transactions') }}
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

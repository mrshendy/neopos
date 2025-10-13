<div>

  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0"><i class="mdi mdi-account-details-outline me-2"></i> {{ __('pos.title_customers_show') }}</h4>
    <div class="d-flex gap-2">
      <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-outline-primary rounded-pill">
        <i class="mdi mdi-pencil-outline"></i> {{ __('pos.btn_edit') }}
      </a>
      <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') }}
      </a>
    </div>
  </div>

  <div class="row g-3">
    {{-- الملف العام --}}
    <div class="col-lg-4">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-light fw-bold">
          <i class="mdi mdi-card-account-details-outline me-2"></i> {{ __('pos.card_profile') }}
        </div>
        <div class="card-body">
          <p class="text-muted small">{{ __('pos.desc_profile') }}</p>
          @php $locale = app()->getLocale(); @endphp
          <div class="mb-2"><span class="text-muted">{{ __('pos.th_code') }}:</span> <span class="fw-semibold">{{ $customer->code }}</span></div>
          <div class="mb-2"><span class="text-muted">{{ __('pos.th_name') }}:</span> <span class="fw-semibold">{{ $customer->getTranslation('legal_name', $locale) }}</span></div>
          <div class="mb-2"><span class="text-muted">{{ __('pos.th_phone') }}:</span> <span class="fw-semibold">{{ $customer->phone ?? '-' }}</span></div>

          <div class="mb-2">
            <span class="text-muted">{{ __('pos.th_status') }}:</span>
            @if($customer->account_status === 'active')
              <span class="badge bg-success">{{ __('pos.status_active') }}</span>
            @elseif($customer->account_status === 'inactive')
              <span class="badge bg-secondary">{{ __('pos.status_inactive') }}</span>
            @else
              <span class="badge bg-warning text-dark">{{ __('pos.status_suspended') }}</span>
            @endif
          </div>

          <hr>

          <div class="mb-2"><span class="text-muted">{{ __('pos.th_city') }}:</span> <span class="fw-semibold">{{ $customer->city ?? optional($customer->cityRel)->name ?? '-' }}</span></div>
          <div class="mb-2"><span class="text-muted">{{ __('pos.th_area') }}:</span> <span class="fw-semibold">{{ optional($customer->area)->name ?? '-' }}</span></div>
          <div class="mb-2"><span class="text-muted">{{ __('pos.th_price_category') }}:</span>
            <span class="fw-semibold">{{ optional($customer->priceCategory)->getTranslation('name', $locale) ?? '-' }}</span>
          </div>
        </div>
      </div>
    </div>

    {{-- المعاملات والأرصدة --}}
    <div class="col-lg-8">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-light fw-bold">
          <i class="mdi mdi-cash-multiple me-2"></i> {{ __('pos.card_summary') }}
        </div>
        <div class="card-body">
          <p class="text-muted small">{{ __('pos.desc_summary') }}</p>
          <div class="row text-center">
            <div class="col-md-3 mb-3">
              <div class="p-3 border rounded-3">
                <div class="text-muted small">{{ __('pos.th_balance') }}</div>
                <div class="fs-5 fw-bold">{{ number_format($customer->balance, 2) }}</div>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="p-3 border rounded-3">
                <div class="text-muted small">{{ __('pos.th_credit_limit') }}</div>
                <div class="fs-5 fw-bold">{{ number_format($customer->credit_limit, 2) }}</div>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="p-3 border rounded-3">
                <div class="text-muted small">{{ __('pos.th_available') }}</div>
                <div class="fs-5 fw-bold">{{ number_format(max($customer->credit_limit - $customer->balance, 0), 2) }}</div>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="p-3 border rounded-3">
                <div class="text-muted small">{{ __('pos.th_open_invoices') }}</div>
                <div class="fs-5 fw-bold">{{ $customer->transactions()->where('type','invoice')->count() }}</div>
              </div>
            </div>
          </div>

          <hr>

          {{-- آخر المعاملات --}}
          <h6 class="fw-bold mb-2"><i class="mdi mdi-file-document-outline me-1"></i> {{ __('pos.card_transactions') }}</h6>
          <div class="table-responsive">
            <table class="table table-sm table-striped align-middle">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>{{ __('pos.th_type') }}</th>
                  <th>{{ __('pos.th_reference') }}</th>
                  <th>{{ __('pos.th_date') }}</th>
                  <th>{{ __('pos.th_value') }}</th>
                </tr>
              </thead>
              <tbody>
                @forelse($customer->transactions()->latest()->limit(10)->get() as $tx)
                  <tr>
                    <td>{{ $tx->id }}</td>
                    <td>{{ $tx->type }}</td>
                    <td>{{ $tx->reference_no }}</td>
                    <td>{{ $tx->date }}</td>
                    <td>{{ number_format($tx->amount, 2) }}</td>
                  </tr>
                @empty
                  <tr><td colspan="5" class="text-center text-muted">{{ __('pos.no_transactions') }}</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

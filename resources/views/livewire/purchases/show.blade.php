<div class="container-fluid">
    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="mdi mdi-receipt-text-outline me-1"></i>
                {{ __('pos.purchase_show_title') }} #{{ $row->purchase_no }}
            </h5>
            <div class="d-flex gap-2">
                <a href="{{ route('purchases.print', $row->id) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="mdi mdi-printer"></i> {{ __('pos.print') }}
                </a>
                <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="mdi mdi-arrow-left"></i> {{ __('pos.back') }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-3"><strong>{{ __('pos.purchase_no') }}</strong><div>{{ $row->purchase_no }}</div></div>
                <div class="col-md-3"><strong>{{ __('pos.purchase_date') }}</strong><div>{{ $row->purchase_date }}</div></div>
                <div class="col-md-3"><strong>{{ __('pos.delivery_date') }}</strong><div>{{ $row->supply_date ?? '—' }}</div></div>
                <div class="col-md-3"><strong>{{ __('pos.status') }}</strong><div>{{ __('pos.status_'.$row->status) }}</div></div>
                <div class="col-md-6"><strong>{{ __('pos.supplier') }}</strong><div>{{ $row->supplier->name ?? '—' }}</div></div>
                <div class="col-md-6"><strong>{{ __('pos.warehouse') }}</strong><div>{{ $row->warehouse->name ?? '—' }}</div></div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('pos.product') }}</th>
                            <th class="text-center">{{ __('pos.unit') }}</th>
                            <th class="text-center">{{ __('pos.qty') }}</th>
                            <th class="text-end">{{ __('pos.unit_price') }}</th>
                            <th class="text-end">{{ __('pos.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lines as $i => $l)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $l->product->name ?? '—' }}</td>
                                <td class="text-center">{{ $l->unit->name ?? '—' }}</td>
                                <td class="text-center">{{ number_format((float)$l->qty, 4) }}</td>
                                <td class="text-end">{{ number_format((float)$l->unit_price, 2) }}</td>
                                <td class="text-end">{{ number_format((float)$l->qty * (float)$l->unit_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end">{{ __('pos.grand_total') }}</th>
                            <th class="text-end">{{ number_format((float)$row->grand_total, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold"><i class="mdi mdi-file-eye-outline me-1"></i> {{ __('pos.invoice_view') }} ({{ $row->sale_no }})</h4>
        <button class="btn btn-outline-secondary" onclick="window.print()"><i class="mdi mdi-printer"></i> {{ __('pos.print') }}</button>
    </div>

    <div class="card shadow-sm rounded-4 stylish-card mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><strong>{{ __('pos.sale_no') }}</strong><div>{{ $row->sale_no }}</div></div>
                <div class="col-md-3"><strong>{{ __('pos.sale_date') }}</strong><div>{{ $row->sale_date }}</div></div>
                <div class="col-md-3"><strong>{{ __('pos.customer') }}</strong><div>{{ optional($row->customer)->name }}</div></div>
                <div class="col-md-3"><strong>{{ __('pos.warehouse') }}</strong><div>{{ optional($row->warehouse)->name }}</div></div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>{{ __('pos.product') }}</th>
                    <th class="text-center">{{ __('pos.qty') }}</th>
                    <th class="text-center">{{ __('pos.unit_price') }}</th>
                    <th class="text-end">{{ __('pos.line_total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($row->lines as $l)
                <tr>
                    <td>{{ optional($l->product)->name }}</td>
                    <td class="text-center">{{ number_format($l->qty, 4) }}</td>
                    <td class="text-center">{{ number_format($l->unit_price, 2) }}</td>
                    <td class="text-end">{{ number_format($l->line_total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr><th colspan="3" class="text-end">{{ __('pos.subtotal') }}</th><th class="text-end">{{ number_format($row->subtotal,2) }}</th></tr>
                <tr><th colspan="3" class="text-end">{{ __('pos.discount') }}</th><th class="text-end">{{ number_format($row->discount,2) }}</th></tr>
                <tr><th colspan="3" class="text-end">{{ __('pos.tax') }}</th><th class="text-end">{{ number_format($row->tax,2) }}</th></tr>
                <tr><th colspan="3" class="text-end">{{ __('pos.grand_total') }}</th><th class="text-end">{{ number_format($row->grand_total,2) }}</th></tr>
            </tfoot>
        </table>
    </div>
</div>

<div style="padding:24px;font-family:Tahoma,Arial,sans-serif">
    <h3 style="margin:0 0 12px 0">{{ __('pos.purchase_invoice') }} #{{ $row->purchase_no }}</h3>
    <div style="display:flex;gap:24px;margin-bottom:12px">
        <div><strong>{{ __('pos.purchase_date') }}:</strong> {{ $row->purchase_date }}</div>
        <div><strong>{{ __('pos.delivery_date') }}:</strong> {{ $row->supply_date ?? '—' }}</div>
        <div><strong>{{ __('pos.status') }}:</strong> {{ __('pos.status_'.$row->status) }}</div>
    </div>
    <div style="display:flex;gap:24px;margin-bottom:12px">
        <div><strong>{{ __('pos.supplier') }}:</strong> {{ $row->supplier->name ?? '—' }}</div>
        <div><strong>{{ __('pos.warehouse') }}:</strong> {{ $row->warehouse->name ?? '—' }}</div>
    </div>

    <table width="100%" border="1" cellspacing="0" cellpadding="6" style="border-collapse:collapse;font-size:13px">
        <thead>
            <tr style="background:#f5f5f5">
                <th>#</th>
                <th>{{ __('pos.product') }}</th>
                <th>{{ __('pos.unit') }}</th>
                <th style="text-align:center">{{ __('pos.qty') }}</th>
                <th style="text-align:right">{{ __('pos.unit_price') }}</th>
                <th style="text-align:right">{{ __('pos.total') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lines as $i => $l)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $l->product->name ?? '—' }}</td>
                    <td>{{ $l->unit->name ?? '—' }}</td>
                    <td style="text-align:center">{{ number_format((float)$l->qty, 4) }}</td>
                    <td style="text-align:right">{{ number_format((float)$l->unit_price, 2) }}</td>
                    <td style="text-align:right">{{ number_format((float)$l->qty * (float)$l->unit_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" style="text-align:right">{{ __('pos.grand_total') }}</th>
                <th style="text-align:right">{{ number_format((float)$row->grand_total, 2) }}</th>
            </tr>
        </tfoot>
    </table>

    <script>
        // اطبع تلقائياً عند الفتح (اختياري)
        window.addEventListener('load', () => window.print());
    </script>
</div>

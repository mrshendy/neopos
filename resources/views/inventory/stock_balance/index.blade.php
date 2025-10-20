@extends('layouts.master')

@section('content')
<div class="container-fluid">

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

    <div class="card shadow-sm rounded-4 stylish-card mb-4">
        <div class="card-header bg-light fw-bold d-flex align-items-center justify-content-between">
            <div><i class="mdi mdi-clipboard-text-outline me-1"></i> {{ __('pos.balance_title') }}</div>
            <form action="{{ route('inv.balance.rebuild') }}" method="post" onsubmit="return confirm('{{ __('pos.rebuild_confirm') }}');">
                @csrf
                <button class="btn btn-sm btn-outline-warning rounded-pill px-3">
                    <i class="mdi mdi-refresh"></i> {{ __('pos.rebuild_btn') }}
                </button>
            </form>
        </div>

        <div class="card-body">
            <form method="get" class="row g-2 mb-3">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">{{ __('pos.warehouse') }}</label>
                    <select name="warehouse_id" class="form-select">
                        <option value="">{{ __('pos.all') }}</option>
                        @foreach($warehouses as $w)
                            <option value="{{ $w->id }}" {{ (int)($wid ?? 0) === (int)$w->id ? 'selected' : '' }}>
                                {{-- استخراج اسم المخزن حسب اللغة (نفس طريقة الكنترولر) --}}
                                @php
                                    $name = $w->name;
                                    if (is_string($name) && str_starts_with(trim($name), '{')) {
                                        $arr = json_decode($name, true) ?: [];
                                        $name = $arr[app()->getLocale()] ?? $arr['ar'] ?? $w->name;
                                    }
                                @endphp
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">{{ __('pos.product') }}</label>
                    <select name="product_id" class="form-select">
                        <option value="">{{ __('pos.all') }}</option>
                        @foreach($products as $p)
                            @php
                                $pName = $p->name;
                                if (is_string($pName) && str_starts_with(trim($pName), '{')) {
                                    $ja = json_decode($pName, true) ?: [];
                                    $pName = $ja[app()->getLocale()] ?? $ja['ar'] ?? $p->name;
                                }
                            @endphp
                            <option value="{{ $p->id }}" {{ (int)($pid ?? 0) === (int)$p->id ? 'selected' : '' }}>
                                {{ $pName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">{{ __('pos.search') }}</label>
                    <input type="text" name="q" class="form-control" value="{{ $q }}" placeholder="{{ __('pos.search_ph') }}">
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary rounded-pill px-4"><i class="mdi mdi-magnify"></i> {{ __('pos.search_btn') }}</button>
                    <a href="{{ route('inv.balance') }}" class="btn btn-outline-secondary rounded-pill px-3">
                        <i class="mdi mdi-backspace-outline"></i> {{ __('pos.clear') }}
                    </a>
                </div>
            </form>

            @if(collect($balances)->isEmpty())
                <div class="text-muted small">
                    {{ __('pos.no_data') }}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('pos.warehouse') }}</th>
                                <th>{{ __('pos.product') }}</th>
                                <th class="text-center" style="width:120px">{{ __('pos.uom') }}</th>
                                <th class="text-end" style="width:160px">{{ __('pos.onhand') }}</th>
                                <th style="width:180px">{{ __('pos.last_update') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($balances as $b)
                                @php
                                    $qty  = (float)$b->onhand;
                                    $isNeg = $qty < 0;
                                    $ts   = $b->ts ? \Illuminate\Support\Carbon::parse($b->ts)->format('Y-m-d H:i') : '—';
                                @endphp
                                <tr>
                                    <td>{{ $b->warehouse_name }}</td>
                                    <td>{{ $b->product_name }}</td>
                                    <td class="text-center"><span class="badge bg-light text-dark border">{{ $b->uom }}</span></td>
                                    <td class="text-end {{ $isNeg ? 'text-danger fw-bold' : 'text-success' }}">{{ number_format($qty, 4) }}</td>
                                    <td>{{ $ts }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $balances->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.stylish-card{border:1px solid rgba(0,0,0,.06)}
.table thead th{background:#f8f9fc; white-space:nowrap}
.table td,.table th{vertical-align:middle}
</style>
@endsection

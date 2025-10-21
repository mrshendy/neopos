@extends('layouts.master')

@section('title', __('pos.finance_title_show'))

@section('content')
<div class="page-wrap">
    <div class="card rounded-4 shadow-sm stylish-card">
        <div class="card-header bg-light fw-bold">
            <i class="mdi mdi-eye-outline me-1"></i> {{ __('pos.finance_title_show') }}
        </div>
        <div class="card-body">
            {{-- استخدم نفس منطق resolveName لعرض الإسم حسب اللغة --}}
            @php
                $row = \App\models\finance\finance::findOrFail($id);
                $resolveName = function($val){
                    if (is_string($val) && strlen($val) && $val[0]==='{'){
                        $arr = json_decode($val,true) ?: [];
                        $loc = app()->getLocale();
                        return $arr[$loc] ?? $arr['ar'] ?? $arr['en'] ?? $val;
                    }
                    return $val;
                };
            @endphp

            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="p-3 border rounded-3">
                        <div class="text-muted">{{ __('pos.col_name') }}</div>
                        <div class="fw-bold">{{ $resolveName($row->name) }}</div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="p-3 border rounded-3">
                        <div class="text-muted">{{ __('pos.col_prefix') }}</div>
                        <div class="fw-bold">{{ $row->receipt_prefix }}</div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="p-3 border rounded-3">
                        <div class="text-muted">{{ __('pos.col_next_no') }}</div>
                        <div class="fw-bold">{{ $row->next_number }}</div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-lg-3">
                    <div class="p-3 border rounded-3">
                        <div class="text-muted">{{ __('pos.col_branch') }}</div>
                        <div class="fw-bold">{{ $row->branch_id ?: '—' }}</div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="p-3 border rounded-3">
                        <div class="text-muted">{{ __('pos.col_currency') }}</div>
                        <div class="fw-bold">{{ $row->currency_id ?: '—' }}</div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="p-3 border rounded-3">
                        <div class="text-muted">{{ __('pos.col_status') }}</div>
                        <span class="badge {{ $row->status==='active'?'bg-success':'bg-secondary' }}">
                            {{ $row->status==='active'?__('pos.status_active'):__('pos.status_inactive') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('finance.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.master')

@section('title') {{ __('pos.btn_new_warehouse') }} @stop

@section('content')
<div class="page-wrap">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0"><i class="mdi mdi-warehouse me-2"></i> {{ __('pos.btn_new_warehouse') }}</h4>
        <a href="{{ route('inventory.warehouses.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
            <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_cancel') }}
        </a>
    </div>

    @livewire('inventory.warehouses.create')
</div>
@endsection

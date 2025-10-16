@extends('layouts.master')

@section('title') {{ __('pos.inventory_transactions_create') }} @stop

@section('content')
<div class="page-wrap">
    <div class="d-flex align-products-center justify-content-between mb-3">
        <h4 class="mb-0"><i class="mdi mdi-swap-horizontal-bold me-2"></i> {{ __('pos.inventory_edit') }}</h4>
        <a href="{{ route('inventory.transactions.edit') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
            <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_cancel') }}
        </a>
    </div>

    @livewire('inventory.transactions.edit', ['id' => $id])
</div>
@endsection

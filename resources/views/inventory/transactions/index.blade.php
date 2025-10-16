@extends('layouts.master')

@section('title') {{ __('pos.inventory_transactions_title') }} @stop

@section('content')
<div class="page-wrap">
    <div class="d-flex align-products-center justify-content-between mb-3">
        <h4 class="mb-0">
            <i class="mdi mdi-swap-horizontal-bold me-2"></i> {{ __('pos.inventory_transactions_title') }}
        </h4>
        <a href="{{ route('inventory.transactions.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
            <i class="mdi mdi-plus-circle-outline"></i> {{ __('pos.btn_new_transaction') }}
        </a>
    </div>

    @livewire('inventory.transactions.index')
</div>
@endsection

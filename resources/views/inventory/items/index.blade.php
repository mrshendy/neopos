@extends('layouts.master')

@section('title') {{ __('pos.inventory_items_title') }} @stop

@section('content')
<div class="page-wrap">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">
            <i class="mdi mdi-package-variant-closed me-2"></i> {{ __('pos.inventory_items_title') }}
        </h4>
        <a href="{{ route('inventory.items.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
            <i class="mdi mdi-plus-circle-outline"></i> {{ __('pos.btn_new_item') }}
        </a>
    </div>

    @livewire('inventory.items.index')
</div>
@endsection

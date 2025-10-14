@extends('layouts.master')

@section('title') {{ __('pos.inventory_items_title') }} @stop

@section('content')
<div class="page-wrap">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0"><i class="mdi mdi-pencil-outline me-2"></i> {{ __('pos.inventory_items_title') }}</h4>
        <a href="{{ route('inventory.items.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
            <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_cancel') }}
        </a>
    </div>

    {{-- تمرير الـ ID من الراوت --}}
    @livewire('inventory.items.edit', ['item_id' => request()->route('item')])
</div>
@endsection

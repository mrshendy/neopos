@extends('layouts.master')

@section('title') {{ __('pos.add_new_item') }} @stop

@section('content')
<div class="page-wrap">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0"><i class="mdi mdi-package-variant-plus me-2"></i> {{ __('pos.add_new_item') }}</h4>
        <a href="{{ route('inventory.manage') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
            <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_cancel') }}
        </a>
    </div>

    @livewire('inventory.items.create')
</div>
@endsection

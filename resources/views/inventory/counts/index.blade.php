@extends('layouts.master')

@section('title') {{ __('pos.inventory_counts_title') }} @stop

@section('content')
<div class="page-wrap">
  <div class="d-flex align-products-center justify-content-between mb-3">
      <h4 class="mb-0"><i class="mdi mdi-clipboard-list-outline me-2"></i> {{ __('pos.inventory_counts_title') }}</h4>
      <a href="{{ route('inventory.manage') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
          <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_back') }}
      </a>
  </div>

  @livewire('inventory.counts.index')
</div>
@endsection

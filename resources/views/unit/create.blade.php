@extends('layouts.master')

@section('title', __('pos.units_create'))

@section('content')
<div class="page-wrap">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0"><i class="mdi mdi-ruler me-2"></i> {{ __('pos.units_create') }}</h4>
        <a href="{{ route('units.index') }}" class="btn btn-outline-secondary rounded-pill shadow-sm">
            <i class="mdi mdi-arrow-left"></i> {{ __('pos.btn_cancel') }}
        </a>
    </div>

    @livewire('unit.create')
</div>
@endsection

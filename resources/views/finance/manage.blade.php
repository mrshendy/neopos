@extends('layouts.master')

@section('title', isset($id) ? __('pos.finance_title_edit') : __('pos.finance_title_create'))

@section('content')
<div class="page-wrap">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-3">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <livewire:finance.manage :id="isset($id) ? $id : null" />
</div>
@endsection

{{-- resources/views/finance/handovers_manage.blade.php --}}
@extends('layouts.master')
@section('title', __('pos.handover_title_manage') ?? 'إدارة استلام/تسليم الخزينة')
@section('content')
    <livewire:finance-handovers.manage :id="$id ?? null" />
@endsection

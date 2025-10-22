{{-- resources/views/finance/receipts_manage.blade.php --}}
@extends('layouts.master')
@section('title', __('pos.receipts_title_manage') ?? 'إدارة إيصال')
@section('content')
    <livewire:finance-receipts.manage :id="$id ?? null" />
@endsection

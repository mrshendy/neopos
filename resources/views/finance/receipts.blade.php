{{-- resources/views/finance/receipts.blade.php --}}
@extends('layouts.master')
@section('title', __('pos.receipts_title_index') ?? 'الإيصالات')
@section('content')
    <livewire:finance-receipts.index />
@endsection

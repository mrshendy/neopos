{{-- resources/views/finance/handovers.blade.php --}}
@extends('layouts.master')
@section('title', __('pos.handover_title_index') ?? 'استلام/تسليم الخزينة')
@section('content')
    <livewire:finance-handovers.index />
@endsection

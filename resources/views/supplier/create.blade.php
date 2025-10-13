@extends('layouts.master')

@section('title')
    {{ __('pos.supplier_create') }}
@endsection

@section('content')
<div class="container-fluid py-3">
    @livewire('supplier.create')
</div>
@endsection
